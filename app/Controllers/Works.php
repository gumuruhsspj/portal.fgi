<?php

namespace App\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use App\Libraries\EmailPostSender;
use Config\Services;


class Works extends BaseController
{
    // private $session;


    private $link_logo = 'https://portal.fgroupindonesia.com/assets/img/logo.jpg';

    public function __construct() {}

    // fokus di AI Text Generator
    public function generate_text()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['error' => 'Invalid request']);
        }

        // Ambil input
        $customer_type = $this->request->getPost('customer_type');
        $prospect_status = $this->request->getPost('prospect_status');
        $variasi = (int) $this->request->getPost('variasi');
        $use_iconic = $this->request->getPost('use_iconic') == '1';
        $include_wa = $this->request->getPost('include_wa') == '1';
        $program_id = $this->request->getPost('program_id');
        $user_id = session()->get('id_user');

        // Validasi
        if ($variasi < 1) $variasi = 1;
        if ($variasi > 10) $variasi = 10;

        // Ambil data program afiliasi user untuk mendapatkan kode referal
        $memberModel = $this->model_member_afiliasi;
        $member = $memberModel->where(['id_user' => $user_id, 'id_program_afiliasi' => $program_id, 'status' => 'active'])->first();
        if (!$member) {
            return $this->response->setJSON(['error' => 'Program afiliasi tidak ditemukan atau Anda belum bergabung.']);
        }

        $kode_referal = $member['kode_referal'];
        $link_registrasi = base_url('register?ref=' . $kode_referal);
        $wa_user = session()->get('whatsapp') ?? '';

        // Bangun prompt
        $prompt = $this->buildPrompt($customer_type, $prospect_status, $variasi, $use_iconic, $include_wa, $link_registrasi, $wa_user);

        // Panggil Groq API
        $result = $this->callGroqAPI($prompt);

        if (isset($result['error'])) {
            return $this->response->setJSON(['error' => $result['error']]);
        }

        // Parsing hasil menjadi array variasi
        $variasi_text = $this->parseVariasi($result['text'], $variasi);

        return $this->response->setJSON([
            'success' => true,
            'variasi' => $variasi_text,
            'raw' => $result['text'] // untuk debug
        ]);
    }

    private function buildPrompt($customer_type, $prospect_status, $variasi, $use_iconic, $include_wa, $link, $wa)
    {
        $customer_map = [
            'karyawan' => 'karyawan',
            'ibu_rumah_tangga' => 'ibu rumah tangga',
            'owner_bisnis' => 'owner bisnis',
            'umum' => 'masyarakat umum'
        ];
        $customer_label = $customer_map[$customer_type] ?? 'masyarakat umum';

        $status_map = [
            'aware' => 'baru tahu tentang program ini',
            'interested' => 'sudah tertarik',
            'considering' => 'sedang membandingkan dengan program lain',
            'ready' => 'sudah siap untuk mendaftar'
        ];
        $status_label = $status_map[$prospect_status] ?? 'baru tahu';

        $iconic = $use_iconic ? 'Gunakan emoji/ikon yang relevan untuk mempercantik pesan.' : 'Jangan gunakan emoji/ikon.';

        $wa_instruction = $include_wa ? "Sertakan nomor WhatsApp promotor: $wa" : "Jangan sertakan nomor WhatsApp.";
        $link_instruction = "Sertakan link pendaftaran afiliasi: $link";

        $prompt = "Anda adalah asisten pemasaran yang ahli dalam membuat pesan promosi untuk program afiliasi. 
Buatlah $variasi variasi pesan promosi yang berbeda untuk menjaring calon pelanggan dengan profil berikut:
- Target pelanggan: $customer_label
- Status prospect: $status_label
- $iconic
- $wa_instruction
- $link_instruction

Setiap variasi harus memiliki:
1. Judul/pembuka yang menarik
2. Isi pesan yang persuasif (2-3 paragraf)
3. Call-to-action yang jelas

Format output: 
--- VARIASI 1 ---
[isi pesan]
--- VARIASI 2 ---
[isi pesan]
... dst.

Jangan tambahkan teks di luar format tersebut.";

        return $prompt;
    }

    private function callGroqAPI($prompt)
    {
        // --- Coba Groq dulu ---
        $result = $this->tryProvider('groq', $prompt);
        if ($result !== null && !isset($result['error'])) {
            return $result;
        }

        // --- Jika Groq gagal, coba OpenRouter ---
        $result = $this->tryProvider('openrouter', $prompt);
        if ($result !== null && !isset($result['error'])) {
            return $result;
        }

        // Semua gagal
        $lastError = isset($result['error']) ? $result['error'] : 'Tidak diketahui';
        return ['error' => 'Semua provider gagal. Error terakhir: ' . $lastError];
    }

    private function tryProvider($provider, $prompt)
    {
        $apiKey = '';
        $apiUrl = '';
        $modelList = '';
        $providerName = '';

        if ($provider === 'groq') {
            $apiKey = getenv('GROQ_API_KEY');
            $apiUrl = getenv('GROQ_API_URL') . '/chat/completions';
            $modelList = getenv('GROQ_API_MODEL_LIST');
            $providerName = 'Groq';
        } elseif ($provider === 'openrouter') {
            $apiKey = getenv('OPENROUTER_API_KEY');
            $apiUrl = getenv('OPENROUTER_API_URL') . '/chat/completions';
            $modelList = getenv('OPENROUTER_API_MODEL_LIST');
            $providerName = 'OpenRouter';
        } else {
            return ['error' => 'Provider tidak dikenal'];
        }

        if (!$apiKey || !$apiUrl) {
            return ['error' => "API key atau URL untuk $providerName tidak terdefinisi"];
        }

        // Parse daftar model, filter whisper (untuk Groq)
        $models = array_map('trim', explode(',', $modelList));
        if ($provider === 'groq') {
            $models = array_filter($models, function ($m) {
                return stripos($m, 'whisper') === false;
            });
        }

        // Jika tidak ada model, tambahkan fallback default sesuai provider
        if (empty($models)) {
            if ($provider === 'groq') {
                $models = ['llama3-70b-8192', 'mixtral-8x7b-32768'];
            } else {
                // fallback default openrouter model (gratis)
                $models = ['google/gemma-4-31b-it:free', 'nvidia/nemotron-3-nano-30b-a3b:free'];
            }
        }

        // Prioritaskan model tertentu (khusus Groq)
        if ($provider === 'groq') {
            $priority = ['allam-2-7b', 'groq/compound', 'groq/compound-mini'];
            usort($models, function ($a, $b) use ($priority) {
                $posA = array_search($a, $priority);
                $posB = array_search($b, $priority);
                if ($posA === false && $posB === false) return 0;
                if ($posA === false) return 1;
                if ($posB === false) return -1;
                return $posA - $posB;
            });
        }

        $lastError = '';
        foreach ($models as $model) {
            $headers = [
                'Authorization: Bearer ' . $apiKey,
                'Content-Type: application/json'
            ];
            // Tambahkan header khusus untuk OpenRouter
            if ($provider === 'openrouter') {
                $headers[] = 'HTTP-Referer: ' . base_url();
                $headers[] = 'X-Title: Portal FGroup';
            }

            $data = [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'Anda adalah asisten pemasaran yang ahli membuat teks promosi.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.8,
                'max_tokens' => 1500,
                'top_p' => 0.9,
            ];

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                $lastError = 'CURL Error: ' . $curlError;
                continue;
            }

            if ($httpCode != 200) {
                $lastError = 'HTTP ' . $httpCode . ': ' . $response;
                continue;
            }

            $decoded = json_decode($response, true);
            if (isset($decoded['choices'][0]['message']['content'])) {
                return ['text' => $decoded['choices'][0]['message']['content']];
            } else {
                $lastError = 'Unexpected API response: ' . $response;
                continue;
            }
        }

        return ['error' => "Semua model $providerName gagal. Error terakhir: $lastError"];
    }

    private function parseVariasi($rawText, $expected)
    {
        // Pisahkan berdasarkan pola "--- VARIASI X ---"
        $pattern = '/---\s*VARIASI\s*(\d+)\s*---/i';
        $parts = preg_split($pattern, $rawText, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        $result = [];
        for ($i = 0; $i < count($parts); $i += 2) {
            $index = isset($parts[$i]) ? (int)$parts[$i] : count($result) + 1;
            $content = isset($parts[$i + 1]) ? trim($parts[$i + 1]) : '';
            if (!empty($content)) {
                $result[] = ['nomor' => $index, 'teks' => $content];
            }
        }

        // Jika tidak terdeteksi, bagi dengan newline ganda
        if (empty($result)) {
            $blocks = preg_split('/\n\s*\n/', $rawText);
            foreach ($blocks as $idx => $block) {
                if (!empty(trim($block))) {
                    $result[] = ['nomor' => $idx + 1, 'teks' => trim($block)];
                }
            }
        }

        // Batasi sesuai variasi yang diminta
        return array_slice($result, 0, $expected);
    }

    // fokus di Management Kategori Media Promosi
    // ========== MEDIA CATEGORY CRUD ==========
    public function media_category_list()
    {
        $this->is_logged_in();
        // Bisa menggunakan isAJAX() atau tidak, karena kita akan mengembalikan JSON
        // Tapi untuk fleksibilitas, kita bisa mengizinkan baik AJAX maupun tidak, asalkan return JSON.
        $data = $this->model_media_category->findAll(); // atau orderBy etc.
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'success', 'data' => []]); // atau error jika perlu
        }
    }

    public function media_category_add()
    {
        $this->is_logged_in();

        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        // Pastikan data dipastikan bertipe string & di-trim
        $nama = trim((string) $this->request->getPost('nama'));

        if (empty($nama)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama kategori harus diisi']);
        }

        // Gunakan struktur array [ 'kolom_db' => $nilai ]
        $data = array(
            'nama' => $nama
        );

        //echo var_dump($data);
        if ($this->model_media_category->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kategori berhasil ditambahkan']);
        }

        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambahkan kategori']);
    }

    public function media_category_edit($id)
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }
        $data = $this->model_media_category->find($id);
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Kategori tidak ditemukan']);
    }

    public function media_category_update()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
        if (empty($nama)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama kategori harus diisi']);
        }
        if ($this->model_media_category->update($id, ['nama' => $nama])) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kategori berhasil diupdate']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update kategori']);
    }

    public function media_category_delete()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }
        $id = $this->request->getPost('id');
        // Cek apakah kategori masih dipakai oleh media promosi
        $used = $this->model_media_promosi->where('id_kategori', $id)->countAllResults();
        if ($used > 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kategori masih digunakan, tidak bisa dihapus']);
        }
        if ($this->model_media_category->delete($id)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Kategori berhasil dihapus']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal hapus kategori']);
    }


    // tim promotor akses
    public function download_affiliate_media($id)
    {
        $this->is_logged_in();
        $id_user = session()->get('id_user');
        $username = session()->get('username');
        $wa_user = session()->get('whatsapp') ?? '';

        // Ambil media promosi
        $media = $this->model_media_promosi->find($id);
        if (!$media) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Ambil data member afiliasi user (untuk kode referal)
        $memberModel = $this->model_member_afiliasi;
        $member = $memberModel
            ->where('id_user', $id_user)
            ->where('status', 'active')
            ->first();

        if (!$member) {
            return $this->response->setJSON(['error' => 'Anda belum bergabung dengan program afiliasi manapun.']);
        }

        $kode_referal = $member['kode_referal'];
        $link_registrasi = base_url('register?ref=' . $kode_referal);

        // Parse config dari media
        $config = json_decode($media['config'], true) ?: [];

        // Path gambar asli
        $imagePath = FCPATH . $media['image'];
        if (!file_exists($imagePath)) {
            throw new \Exception('Gambar tidak ditemukan');
        }

        // Load gambar utama
        $info = getimagesize($imagePath);
        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                throw new \Exception('Format gambar tidak didukung');
        }

        // --- Overlay Teks WA (ganti dengan WA user) ---
        $wa_text = $config['wa_text'] ?? 'Klik untuk chat';
        // Ganti placeholder %wa% dengan nomor WA user
        $wa_text = str_replace('%wa%', $wa_user, $wa_text);
        // Jika tidak ada placeholder, tambahkan WA di akhir
        if (strpos($wa_text, '%wa%') === false && !empty($wa_user)) {
            $wa_text = $wa_text . ' - WA: ' . $wa_user;
        }

        $wa_x         = $config['wa_x'] ?? 50;
        $wa_y         = $config['wa_y'] ?? 50;
        $wa_font_size = $config['wa_font_size'] ?? 24;
        $wa_color     = $config['wa_color'] ?? '#ffffff';

        list($r, $g, $b) = sscanf($wa_color, "#%02x%02x%02x");
        $textColor = imagecolorallocate($image, $r, $g, $b);

        $fontPath = FCPATH . 'assets/fonts/arial.ttf';
        if (!file_exists($fontPath)) {
            $fontPath = FCPATH . 'assets/fonts/FreeSans.ttf';
        }
        if (file_exists($fontPath)) {
            // Gunakan text dengan WA user
            imagettftext($image, $wa_font_size, 0, $wa_x, $wa_y + $wa_font_size, $textColor, $fontPath, $wa_text);
        }

        // --- Overlay QR Code dengan link referal ---
        $qr_text = $link_registrasi; // Ganti dengan link referal

        $tempQrFile = tempnam(sys_get_temp_dir(), 'qr_');

        try {
            $qrCode = new QrCode(
                $qr_text,
                new Encoding('UTF-8'),
                ErrorCorrectionLevel::Low,
                300,
                0
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrPngString = $result->getString();

            if (!empty($qrPngString)) {
                file_put_contents($tempQrFile, $qrPngString);
            }

            if (file_exists($tempQrFile) && filesize($tempQrFile) > 0) {
                $qrImage = imagecreatefrompng($tempQrFile);
                if ($qrImage !== false) {
                    $qr_x      = $config['qr_x'] ?? 200;
                    $qr_y      = $config['qr_y'] ?? 200;
                    $qr_width  = $config['qr_width'] ?? 80;
                    $qr_height = $config['qr_height'] ?? 80;

                    imagecopyresampled(
                        $image,
                        $qrImage,
                        $qr_x,
                        $qr_y,
                        0,
                        0,
                        $qr_width,
                        $qr_height,
                        imagesx($qrImage),
                        imagesy($qrImage)
                    );
                    imagedestroy($qrImage);
                }
            }
        } catch (\Exception $e) {
            error_log("Error generate QR code: " . $e->getMessage());
        } finally {
            if (file_exists($tempQrFile)) {
                unlink($tempQrFile);
            }
        }

        // --- Output gambar sebagai download ---
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        // Nama file: poster_{username}_{media_id}.png
        $filename = 'poster_' . $username . '_' . $id . '.png';

        return $this->response
            ->setHeader('Content-Type', 'image/png')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($imageData);
    }

    public function submit_rekening_bank()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id_user = session()->get('id_user');
        $nama_bank = trim($this->request->getPost('nama_bank'));
        $nama_pemilik = trim($this->request->getPost('nama_pemilik'));
        $nomor_rekening = trim($this->request->getPost('nomor_rekening'));

        // Validasi
        if (empty($nama_bank) || empty($nama_pemilik) || empty($nomor_rekening)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Semua field harus diisi']);
        }

        // Cari member afiliasi aktif user
        $member = $this->model_member_afiliasi->get_rekening_by_user($id_user);
        if (!$member) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda belum memiliki program afiliasi aktif.']);
        }

        $data = [
            'nama_bank' => $nama_bank,
            'nama_pemilik' => $nama_pemilik,
            'nomor_rekening' => $nomor_rekening,
        ];

        // Upload KTP
        $file_ktp = $this->request->getFile('foto_ktp');
        if ($file_ktp && $file_ktp->isValid() && !$file_ktp->hasMoved()) {
            $newName = 'ktp_' . $id_user . '_' . time() . '.' . $file_ktp->getExtension();
            $file_ktp->move(FCPATH . 'assets/img/uploads/rekening', $newName);
            $data['foto_ktp'] = 'assets/img/uploads/rekening/' . $newName;
        }

        // Upload Selfie
        $file_selfie = $this->request->getFile('foto_selfie');
        if ($file_selfie && $file_selfie->isValid() && !$file_selfie->hasMoved()) {
            $newName = 'selfie_' . $id_user . '_' . time() . '.' . $file_selfie->getExtension();
            $file_selfie->move(FCPATH . 'assets/img/uploads/rekening', $newName);
            $data['foto_selfie'] = 'assets/img/uploads/rekening/' . $newName;
        }

        // Set status menjadi pending
        $data['status_rekening'] = 'pending';

        // Update
        $updated = $this->model_member_afiliasi->update_rekening($member['id'], $data);
        if ($updated) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data rekening berhasil disimpan, menunggu verifikasi admin.']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
        }
    }

    // fokus di Management Promosi

    public function media_promosi_add()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $rules = [
            'nama'        => 'required|min_length[3]',
            'id_kategori' => 'required|integer|is_not_unique[table_media_category.id]',
            'image'       => 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
            'config'      => 'permit_empty|valid_json',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        // Upload gambar
        $file = $this->request->getFile('image');
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'assets/img/uploads/media_promosi', $newName);
        $imagePath = 'assets/img/uploads/media_promosi/' . $newName;

        // Config default
        $config = $this->request->getPost('config');
        if (empty($config)) {
            $config = json_encode([
                'wa_text'      => 'Klik untuk chat',
                'wa_x'         => 50,
                'wa_y'         => 50,
                'wa_font_size' => 24,
                'wa_color'     => '#ffffff',
                'qr_x'         => 200,
                'qr_y'         => 200,
                'qr_width'     => 80,
                'qr_height'    => 80,
                'qr_image'     => '' // nanti bisa diisi path QR
            ]);
        }

        $data = [
            'nama'        => $this->request->getPost('nama'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'image'       => $imagePath,
            'config'      => $config,
        ];

        if ($this->model_media_promosi->insert($data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menyimpan data']);
        }
    }

    // Ambil data untuk edit (AJAX)
    public function media_promosi_edit($id)
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $data = $this->model_media_promosi->getWithCategory($id);
        if ($data) {
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }

    // Update data (AJAX)
    public function media_promosi_update()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id = $this->request->getPost('id');
        $rules = [
            'nama'        => 'required|min_length[3]',
            'id_kategori' => 'required|integer|is_not_unique[table_media_category.id]',
            'config'      => 'permit_empty|valid_json',
        ];

        // Jika ada upload gambar baru, tambahkan aturan
        if ($this->request->getFile('image') && $this->request->getFile('image')->isValid()) {
            $rules['image'] = 'uploaded[image]|max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]';
        }

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'nama'        => $this->request->getPost('nama'),
            'id_kategori' => $this->request->getPost('id_kategori'),
            'config'      => $this->request->getPost('config'),
        ];

        // Cek upload gambar baru
        $file = $this->request->getFile('image');
        if ($file && $file->isValid()) {
            // Hapus gambar lama
            $old = $this->model_media_promosi->find($id);
            if ($old && !empty($old['image']) && file_exists(FCPATH . $old['image'])) {
                unlink(FCPATH . $old['image']);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/uploads/media_promosi', $newName);
            $data['image'] = 'assets/img/uploads/media_promosi/' . $newName;
        }

        if ($this->model_media_promosi->update($id, $data)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Data berhasil diupdate']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal update data']);
        }
    }

    // Hapus data (AJAX)
    public function media_promosi_delete()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $id = $this->request->getPost('id');
        $data = $this->model_media_promosi->find($id);
        if ($data) {
            // Hapus file gambar
            if (!empty($data['image']) && file_exists(FCPATH . $data['image'])) {
                unlink(FCPATH . $data['image']);
            }
            $this->model_media_promosi->delete($id);
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    }

    public function media_promosi_preview($id)
    {
        $this->is_logged_in();
        $media = $this->model_media_promosi->getWithCategory($id);
        if (!$media) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $config = json_decode($media['config'], true) ?: [];

        $allowedBase = 'assets/img/uploads/media_promosi/';
        if (strpos($media['image'], $allowedBase) !== 0) {
            throw new \Exception('Path gambar tidak valid');
        }
        $imagePath = FCPATH . $media['image'];

        if (!file_exists($imagePath)) {
            throw new \Exception('Gambar tidak ditemukan');
        }

        $info = getimagesize($imagePath);
        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($imagePath);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($imagePath);
                break;
            default:
                throw new \Exception('Format gambar tidak didukung');
        }

        $origW = imagesx($image);
        $origH = imagesy($image);

        // Default config (warna default diubah jadi hitam)
        $defaultConfig = [
            'display_width'  => $origW,
            'display_height' => $origH,
            'wa_text'        => 'Klik untuk chat',
            'wa_x'           => 50,
            'wa_y'           => 50,
            'wa_font_size'   => 24,
            'wa_color'       => '#000000', // default hitam
            'qr_x'           => 200,
            'qr_y'           => 200,
            'qr_width'       => 80,
            'qr_height'      => 80,
            'qr_text'        => 'https://example.com'
        ];

        $config = array_merge($defaultConfig, $config);

        // Validasi format warna
        if (!preg_match('/^#[0-9a-f]{6}$/i', $config['wa_color'])) {
            $config['wa_color'] = '#000000';
        }

        $scaleX = $origW / max(1, $config['display_width']);
        $scaleY = $origH / max(1, $config['display_height']);

        // --- Overlay Teks WA ---
        $wa_text = $config['wa_text'];
        $wa_x = (int)($config['wa_x'] * $scaleX);
        $wa_y = (int)($config['wa_y'] * $scaleY);
        $wa_font_size = (int)($config['wa_font_size'] * $scaleY);

        // Parse warna secara manual (lebih aman dari sscanf)
        $hex = ltrim($config['wa_color'], '#');
        if (strlen($hex) == 6) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            $r = $g = $b = 0; // fallback hitam
        }

        // Coba alokasi langsung
        $textColor = imagecolorallocate($image, $r, $g, $b);
        if ($textColor === false || $textColor == -1) {
            // Jika gagal, cari warna terdekat yang tersedia
            $textColor = imagecolorresolve($image, $r, $g, $b);
        }
        // Pastikan tidak -1
        if ($textColor < 0) {
            $textColor = imagecolorresolve($image, 0, 0, 0);
        }

        $fontPath = FCPATH . 'assets/fonts/arial.ttf';
        if (!file_exists($fontPath)) {
            $fontPath = FCPATH . 'assets/fonts/Catboo.woff';
        }
        if (file_exists($fontPath)) {
            imagettftext($image, $wa_font_size, 0, $wa_x, $wa_y + $wa_font_size, $textColor, $fontPath, $wa_text);
        }

        // --- Overlay QR Code ---
        $qr_text = $config['qr_text'] ?? 'https://example.com';
        if (empty($qr_text)) {
            $qr_text = 'https://example.com';
        }

        $qr_x = (int)($config['qr_x'] * $scaleX);
        $qr_y = (int)($config['qr_y'] * $scaleY);
        $qr_width = (int)($config['qr_width'] * $scaleX);
        $qr_height = (int)($config['qr_height'] * $scaleY);

        $tempQrFile = tempnam(sys_get_temp_dir(), 'qr_');

        try {
            $qrCode = new QrCode(
                $qr_text,
                new Encoding('UTF-8'),
                ErrorCorrectionLevel::Low,
                300,
                0
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $qrPngString = $result->getString();

            if (!empty($qrPngString)) {
                file_put_contents($tempQrFile, $qrPngString);
            }

            if (file_exists($tempQrFile) && filesize($tempQrFile) > 0) {
                $qrImage = imagecreatefrompng($tempQrFile);
                if ($qrImage !== false) {
                    imagecopyresampled(
                        $image,
                        $qrImage,
                        $qr_x,
                        $qr_y,
                        0,
                        0,
                        $qr_width,
                        $qr_height,
                        imagesx($qrImage),
                        imagesy($qrImage)
                    );
                    imagedestroy($qrImage);
                } else {
                    error_log("Gagal memuat QR code dari file sementara untuk ID $id");
                }
            } else {
                error_log("QR code gagal digenerate atau file kosong untuk ID $id");
            }
        } catch (\Exception $e) {
            error_log("Error generate QR code: " . $e->getMessage());
        } finally {
            if (file_exists($tempQrFile)) {
                unlink($tempQrFile);
            }
        }

        header('Content-Type: image/png');
        imagepng($image);
        imagedestroy($image);
        exit;
    }


    public function checkout_materi()
    {

        // Ambil data dari POST request (dari jQuery AJAX tadi)
        $materi_id  = $this->request->getPost('materi_id');
        $opsi_paket = $this->request->getPost('opsi_paket');

        // Asumsi: ID User diambil dari session yang sedang login
        $id_user = session()->get('id_user');
        $username = session()->get('username');

        $data_user = $this->model_user->find($id_user);

        // 1. Cek Harga Materi (Contoh: ambil dari model materi)
        $materi = $this->model_materi->find($materi_id);
        if (!$materi) {
            return $this->response->setJSON(['status' => 'failed', 'message' => 'Materi tidak ditemukan!']);
        }

        // Tentukan harga berdasarkan paket (misal: reguler/private)
        if ($opsi_paket == 'paket_kasus_custom') {
            $harga = $materi['biaya_kasus_custom'];
        } else if ($opsi_paket == 'paket_pokok') {
            $harga = $materi['biaya_pokok'];
        } else {
            $harga = $materi['biaya_belajar_sendiri'];
        }

        // 2. Cek apakah saldo user cukup
        $saldo_sekarang = $data_user['balance'];
        if ($saldo_sekarang < $harga) {
            return $this->response->setJSON(['status' => 'failed', 'message' => 'Saldo tidak mencukupi! ' . $saldo_sekarang]);
        }

        $data_balance = array(
            'balance' => $saldo_sekarang - $harga
        );

        $this->model_user->update($id_user, $data_balance);

        // 3. Catat di History Saldo sebagai "Pengeluaran" (Minus)
        $data_history = [
            'id_user'     => $id_user,
            'keterangan'  => 'Pembelian Akses Materi: ' . $materi['judul'] . ' paket ' . $opsi_paket,
            'nominal'      => $harga,
            'jenis' => 'daftar kursus',
            'status'      => 'approved'
        ];

        $insert_history = $this->model_history_saldo->insert($data_history);

        if ($insert_history) {
            // 4. Update Balance di tabel User (Sync Saldo)
            $new_balance = $this->model_history_saldo->get_saldo_by($id_user);
            $this->model_user->update($id_user, ['balance' => $new_balance]);

            // 5. Tambahkan akses materi ke user (tabel user_materi / enrollment)
            // Ini opsional tergantung struktur DB kamu
            $data_akses = [
                'username'   => $username,
                'id_materi'  => $materi_id,
                'paket'      => $opsi_paket,
                'status'     => 'in progress'
            ];

            $this->model_materi->add_student_materi($data_akses);

            $respond = [
                'status'  => 'success',
                'message' => 'Checkout Berhasil! Saldo telah dipotong.'
            ];
        } else {
            $respond = [
                'status'  => 'failed',
                'message' => 'Gagal memproses transaksi!'
            ];
        }

        return $this->response->setJSON($respond);
    }

    public function join_program_afiliasi()
    {
        $this->is_logged_in();
        $id_user = session()->get('id_user');
        $id_program = $this->request->getPost('id_program');

        if (!$id_program) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Program tidak valid']);
        }

        // Cek apakah sudah join

        if ($this->model_member_afiliasi->isJoined($id_user, $id_program)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Anda sudah bergabung']);
        }

        // Generate kode referal
        $kode = $this->model_member_afiliasi->generate_kode_referal($id_user, $id_program);

        $data = [
            'id_program' => $id_program,
            'id_user' => $id_user,
            'kode_referal' => $kode,
            'status' => 'active',
            'user_count_total' => 0,
            'user_count_confirmed_total' => 0,
            'user_cash_paid' => 0,
        ];

        if ($this->model_member_afiliasi->insert($data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Berhasil bergabung! Kode referal Anda: ' . $kode,
                'kode' => $kode
            ]);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal bergabung']);
        }
    }

    public function pembayaran_update()
    {

        $status = $this->request->getPost('status');
        $id     = $this->request->getPost('id'); // Ini ID History Saldo

        // 1. Ambil data history dulu UNTUK tahu siapa pemilik transaksi ini
        $history = $this->model_history_saldo->find($id);
        if (!$history) {
            return $this->response->setJSON(['status' => 'failed', 'message' => 'Data tidak ditemukan!']);
        }

        $target_id_user = $history['id_user'];

        $filter = array(
            'status' => 'approved',
            'id_user' => $target_id_user
        );

        $data = ['status' => $status];

        // 2. Eksekusi Update Status Transaksi
        if ($status != 'deleted') {
            $hasil = $this->model_history_saldo->update($id, $data);
        } else {
            $hasil = $this->model_history_saldo->delete($id);
        }

        // 3. Update Balance User si PEMILIK transaksi
        if ($hasil) {

            $this->model_history_saldo->recalc_saldo_chain($target_id_user);

            $respond = [
                'status' => 'success',
                'message' => 'Update berhasil!'
            ];
        } else {
            $respond = [
                'status' => 'failed',
                'message' => 'Update gagal!'
            ];
        }

        return $this->response->setJSON($respond);
    }

    public function register_new_user()
    {

        // default respond
        $respond = array(
            'status' => 'failed',
            'message' => 'registrasi gagal! Silakan coba lagi.'
        );

        // ambil data dari view\modal_registrasi.php

        // jawab disini
        $nama_lengkap   = $this->request->getPost('nama_lengkap');
        $email          = $this->request->getPost('email_user');
        $wa             = $this->request->getPost('no_wa');
        $jenis             = $this->request->getPost('jenis');

        // we have 4 types:
        // GRATIS, PELAJAR, PENGAJAR REGULER, and PENGAJAR MASTER
        $jenis = strtolower($jenis);

        // cari id nya dari table subscription
        $data_subscription = $this->model_subscription->where('jenis', $jenis)->first();

        if ($jenis == 'gratis' || $jenis == 'pelajar') {
            $usertype = 'peserta';
        }

        if ($jenis == 'pengajar reguler' || $jenis == 'pengajar master') {
            $usertype = 'instruktur';
        }

        $username = substr($email, 0, strpos($email, '@'));

        // buat array data agar masuk ke db
        $data = array(
            'nama_lengkap' => $nama_lengkap,
            'username'  => $username,
            'email' => $email,
            'pass' => generateRandomMixedString(8), // generate random password
            'whatsapp' => $wa,
            'usertype' => $usertype
        );

        // sisipkan id subscription
        if (
            $usertype == 'peserta' ||
            $usertype == 'instruktur'
        ) {
            $data['subscription_id'] = $data_subscription->id;
        }

        // pastikan tdk ada email yg sama sudah mendaftar
        $data_user = $this->model_user->where('email', $email)->first();

        if (!$data_user) {

            $hasil = $this->model_user->insert($data);

            if (!empty($hasil)) {
                $respond = array(
                    'status' => 'success',
                    'message' => 'registrasi berhasil! Silakan cek email Anda untuk informasi selanjutnya.'
                );

                // kirim email
                $data_email = array(
                    'link_portal' => base_url(),
                    'customer_name' => $nama_lengkap,
                    'customer_email' => $email,
                    'temporary_password' => $data['pass'],
                    'link_logo' => $this->link_logo
                );
                $emailSender = new EmailPostSender();

                $html_client = view('email/client/registration_success', $data_email, ['save' => true]);

                // kirim dulu ke client
                $result = $emailSender->sendPost($html_client, $email, null, 'Akun Anda Berhasil Dibuat!', null);

                // check ada ref kode ga?
                $ref = $this->request->getPost('ref');
                if ($ref) {
                    // Cari member afiliasi dengan kode tersebut

                    $member = $this->model_member_afiliasi->where('kode_referal', $ref)->where('status', 'active')->first();
                    if ($member) {
                        // Simpan referal

                        if (!$this->model_referal->hasReferal($data_user['id'])) {
                            $this->model_referal->insert([
                                'id_member_afiliasi' => $member['id'],
                                'id_user' => $data_user['id']
                            ]);
                            // Update total count di member
                            $this->model_member_afiliasi->update($member['id'], ['user_count_total' => $member['user_count_total'] + 1]);
                        }
                    }
                }
            }
        } else {
            $respond = array(
                'status' => 'failed',
                'message' => 'registrasi gagal! Email sudah pernah dipakai.'
            );
        }


        echo json_encode($respond);
    }

    public function send_support_email()
    {

        $cs     = $this->request->getPost('customer_name');
        $e      = $this->request->getPost('email');
        $t      = $this->request->getPost('title');
        $d      = $this->request->getPost('descriptions');

        $ref = generateRandomMixedString(7);

        $data_db = array(
            'email'         => $e,
            'customer_name' => $cs,
            'title'         => $t,
            'descriptions'  => $d,
            'status'        => 'pending',
            'ref_number'    => $ref
        );

        $data_db2 = array(
            'messages'  => 'permintaan baru dari pelanggan bernama ' . $cs,
            'username'  => 'admin', // ditujukan ke admin
            'status'    => 'new'
        );

        // simpan data permintaan ke db
        $this->model_support_tickets->insert($data_db);

        // simpan lagi ke notif system nnti agar dicheck oleh admin
        $this->model_system_notif->insert($data_db2);

        $se = 'no-reply@fgroupindonesia.com';
        $er = 'support@fgroupindonesia.com';




        $emailSender = new EmailPostSender();

        $data_email1 = array(
            'customer_name' => $cs,
            'email'         => $e,
            'title'         => $t,
            'descriptions'  => $d,
            'link_logo'     => $this->link_logo,
            'link_portal'   => base_url(),
            'reference_id' => $ref
        );

        $data_email2 = array(
            'customer_name' => $cs,
            'email'         => $e,
            'title'         => $t,
            'descriptions'  => $d,
            'link_logo'     => $this->link_logo
        );

        $html_client = view('email/client/thank_you_support_sent', $data_email1, ['save' => true]);

        $html_admin = view('email/admin/support_notifications', $data_email2, ['save' => true]);

        // kirim dulu ke client
        $result = $emailSender->sendPost($html_client, $e, $se, 'Permintaan Anda Kami Terima!', $er);

        // kirim lagi ke admin
        $result = $emailSender->sendPost($html_admin, $er, $se, 'Permintaan Pelanggan Baru', $e);

        $hasil = array(
            'status' => 'failed',
            'message' => 'support gagal!',
        );

        if ($result !== false) {
            $hasil = array(
                'status' => 'success',
                'message' => 'berhasil!',
            );
        }

        echo json_encode($hasil);
    }

    public function verify_login()
    {
        $u = $this->request->getPost('username');
        $p = $this->request->getPost('pass');
        $ut = $this->request->getPost('usertype');

        if (strpos($u, '@') !== false) {
            $u = substr($u, 0, strpos($u, '@'));
        }

        $data = array(
            'username' => $u,
            'pass' => $p
        );

        $valid_pass = $this->model_user->valid($data);

        // store important data
        if ($valid_pass) {
            $data_user = $this->model_user->get_by_username($u);
            $this->session->set('propic', $data_user->propic);
            $this->session->set('usertype', $data_user->usertype);
            $this->session->set('id', $data_user->id);
            $this->session->set('id_user', $data_user->id);
            $this->session->set('username', $u);
            $this->session->set('nama_lengkap', $data_user->nama_lengkap);
        }

        if (!$valid_pass) {

            $this->session->set('status-logged-in', 'invalid');
            return redirect()->to('/?error=invalid');
        }

        $this->session->set('status-logged-in', 'valid');


        return redirect()->to('/homepage');
    }

    public function comments_rating_all()
    {

        $result = array(
            'status' => 'invalid'
        );

        // id is idmateri
        $id_materi = $this->request->getPost('id');


        $result = $this->model_materi->get_all_comments_rating($id_materi);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
            $end_result['data'] = $result;
        }

        echo json_encode($end_result);
    }

    public function daily_notes_all()
    {

        $result = array(
            'status' => 'invalid'
        );

        $d = $this->request->getPost('date_created');

        $data = array(
            'DATE(date_created)' => $d
        );

        $result = $this->model_daily_notes->get_all_by($data);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
            $end_result['data'] = $result;
        }

        echo json_encode($end_result);
    }

    public function daily_notes_update()
    {

        $result = array(
            'status' => 'invalid'
        );

        $u = $this->request->getPost('username');
        $n = $this->request->getPost('notes');
        $id    = $this->request->getPost('id');

        $data = array(
            'username' => $u,
            'notes' => $n
        );

        $result = $this->model_daily_notes->update_existing($data, $id);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function daily_notes_delete()
    {

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_daily_notes->delete_existing($id);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
        }

        echo json_encode($result);
    }

    public function daily_notes_add()
    {

        $result = array(
            'status' => 'invalid'
        );

        $u = $this->request->getPost('username');
        $n = $this->request->getPost('notes');

        $data = array(
            'username'    => $u,
            'notes'       => $n
        );

        $this->model_daily_notes->insert_new($data);

        $result['status'] = 'valid';

        echo json_encode($result);
    }


    public function materi_delete()
    {

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_materi->delete_existing($id);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
        }

        echo json_encode($result);
    }

    public function materi_edit()
    {

        $id          = $this->request->getPost('id');

        $filter = array(
            'id' => $id
        );

        $returned_value = $this->model_materi->get_by($filter);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
        }

        echo json_encode($result);
    }

    public function comments_rating_add()
    {
        $result = array(
            'status' => 'invalid'
        );

        $id_materi = $this->request->getPost('materi_id');
        $username = $this->request->getPost('username');
        $comments = $this->request->getPost('comments');
        $rating = $this->request->getPost('rating');

        $data = array(
            'id_materi' => $id_materi,
            'username'  => $username,
            'comments'  => $comments,
            'ratings'    => $rating
        );

        $this->model_materi->insert_new_comments_rating($data);

        $result['status'] = 'valid';

        echo json_encode($result);
    }

    public function materi_paket_update()
    {

        // terima data dari ajax
        $id_materi = $this->request->getPost('materi_id');
        $paket = $this->request->getPost('paket');
        $biaya_pokok = $this->request->getPost('biaya_pokok');
        $biaya_belajar_sendiri = $this->request->getPost('biaya_belajar_sendiri');
        $biaya_kasus_custom = $this->request->getPost('biaya_kasus_custom');
        $rilis_sertifikat = $this->request->getPost('rilis_sertifikat');

        $data = array(
            'biaya_pokok' => $biaya_pokok,
            'biaya_belajar_sendiri' => $biaya_belajar_sendiri,
            'biaya_kasus_custom'    => $biaya_kasus_custom,
            'rilis_sertifikat' => $rilis_sertifikat
        );

        foreach ($paket as $p) {
            if ($p == 'paket_belajar_sendiri') {
                $data['paket_belajar_sendiri'] = 'yes';
            }
            if ($p == 'paket_bimbingan') {
                $data['paket_bimbingan'] = 'yes';
            }
            if ($p == 'paket_kasus_custom') {
                $data['paket_kasus_custom'] = 'yes';
            }
        }

        $result = $this->model_materi->update($id_materi, $data);

        $end_result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        if (!empty($result)) {
            $end_result['status'] = "valid";
            $end_result['message'] = "paket berhasil diupdate!";
        }

        echo json_encode($end_result);
    }

    public function pembahasan_delete()
    {

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_materi->delete_existing_pembahasan($id);

        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['message'] = 'pembahasan berhasil dihapus!';
        }

        echo json_encode($result);
    }

    public function pembahasan_bab_delete()
    {

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_materi->delete_existing_bab($id);

        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['message'] = 'bab berhasil dihapus!';
        }

        echo json_encode($result);
    }

    public function pembahasan_bab_update()
    {

        // terima post request untuk ini
        /*  id: id_babna,
        id_materi : id_materina,
        id_user: id_userna,
        judul: judul_na,
        deskripsi: deskripsi_na */
        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        $id = $this->request->getPost('id');
        $id_materi = $this->request->getPost('id_materi');
        $id_user = $this->request->getPost('id_user');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');

        $data = array(
            'id_materi'   => $id_materi,
            'id_user'     => $id_user,
            'judul'       => $judul,
            'deskripsi'  => $deskripsi
        );

        $result_update = $this->model_materi->update_existing_bab($data, $id);
        if (!empty($result_update)) {
            $result['status'] = 'valid';
            $result['message'] = 'bab berhasil diupdate!';
        }

        echo json_encode($result);
    }

    public function download_materi($id_materi)
    {
        $this->is_logged_in(); // Pastikan sudah login

        $data = $this->get_user_data();
        $us = $data['username'];

        // Validasi apakah user terdaftar untuk materi ini
        $data_student_materi = $this->model_materi->get_subscribed_materi($id_materi, $us);

        // Cek status pembayaran juga
        if ($data_student_materi && $data_student_materi->status != 'error' && $data_student_materi->status != 'delete request') {

            $fileName = $data_student_materi->attachment;
            $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'materi' . DIRECTORY_SEPARATOR . $fileName;

            //echo $filePath;

            if (file_exists($filePath)) {
                // CodeIgniter 4 Response Download
                return $this->response->download($filePath, null);
            } else {
                return "File tidak ditemukan di server.";
            }
        }

        return "Akses ditolak";
    }

    public function pembahasan_bab_add()
    {

        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        $id_materi = $this->request->getPost('id_materi');
        $id_materi_custom = $this->request->getPost('id_materi_custom');
        $id_user = $this->request->getPost('id_user');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');

        $data = array(
            'id_materi'   => $id_materi,
            'id_user'     => $id_user,
            'judul'       => $judul,
            'deskripsi'  => $deskripsi
        );

        if (!empty($id_materi_custom)) {
            $data['id_materi_custom'] = $id_materi_custom;
        }

        $this->model_materi->insert_new_pembahasan_bab($data);

        $result['status'] = 'valid';
        $result['message'] = 'bab berhasil ditambahkan!';

        echo json_encode($result);
    }

    public function pembahasan_next_no_urut()
    {

        // kasih id_bab, cari no urut tertinggi, terus return no urut selanjutnya
        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        $id_bab = $this->request->getPost('id_bab');
        $id_custom = $this->request->getPost('id_materi_custom');

        // ambil bab untuk umum? atau custom?
        if (empty($id_custom)) {
            $data = $this->model_materi->get_highest_ordering_index($id_bab);
        } else {
            $data = $this->model_materi->get_custom_highest_ordering_index($id_bab, $id_custom);
        }

        if ($data) {
            $next_no_urut = $data->ordering_index + 1;
        } else {
            $next_no_urut = 1;
        }

        $result['status'] = 'valid';
        $result['data'] = $next_no_urut;

        echo json_encode($result);
    }

    public function pembahasan_display()
    {
        // Ambil ID dari request POST
        $id = $this->request->getPost('id_pembahasan');

        // 1. Ambil data pembahasan saat ini
        $current_data = $this->model_materi->get_pembahasan_by(['id' => $id]);

        if ($current_data) {
            // 2. Cek apakah ada pembahasan sebelum dan sesudahnya
            // Kita kirim id_bab dan ordering_index dari data yang baru kita ambil
            $navigasi = $this->model_materi->get_navigasi_pembahasan(
                $current_data->id_bab,
                $current_data->ordering_index
            );

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => $current_data,
                'hasNext' => ($navigasi['next_id'] !== null), // true jika ada id-nya
                'hasBack' => ($navigasi['prev_id'] !== null), // true jika ada id-nya
                'next_id' => $navigasi['next_id'], // kirim ID-nya biar frontend bisa request lagi
                'prev_id' => $navigasi['prev_id']
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    public function pembahasan_completed()
    {

        $result = array(
            'status' => 'error',
            'message' => 'materi error'
        );

        $id = $this->request->getPost('id_materi');

        $result_update = $this->model_materi->update_status($id, 'completed');

        if (!empty($result_update)) {
            $result['status'] = 'success';
            $result['message'] = 'status materi student berhasil diupdate!';
        }

        echo json_encode($result);
    }

    public function pembahasan_update()
    {

        // terima judul, deskripsi dan ordering_index
        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        $id = $this->request->getPost('id');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');
        $ordering_index = $this->request->getPost('ordering_index');

        $data = array(
            'judul'       => $judul,
            'deskripsi'  => $deskripsi,
            'ordering_index' => $ordering_index
        );

        $result_update = $this->model_materi->update_existing_pembahasan($data, $id);
        if (!empty($result_update)) {
            $result['status'] = 'valid';
            $result['message'] = 'pembahasan berhasil diupdate!';
        }

        echo json_encode($result);
    }

    public function pembahasan_edit()
    {

        $id          = $this->request->getPost('id');

        $filter = array(
            'id' => $id
        );

        $returned_value = $this->model_materi->get_pembahasan_by($filter);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
        }

        echo json_encode($result);
    }

    public function pembahasan_add()
    {

        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );


        $id_bab = $this->request->getPost('id_bab');
        $id_materi = $this->request->getPost('id_materi');
        $id_materi_custom = $this->request->getPost('id_materi_custom');
        $id_user = $this->request->getPost('id_user');
        $ordering_index = $this->request->getPost('ordering_index');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');

        $data = array(
            'id_materi' => $id_materi,
            'id_bab'   => $id_bab,
            'id_user'     => $id_user,
            'ordering_index'   => $ordering_index,
            'judul'       => $judul,
            'deskripsi'  => $deskripsi
        );

        if (!empty($id_materi_custom)) {
            // berarti pembahasan custom
            $data['id_materi_custom'] = $id_materi_custom;

            // ga pake 2 column ini klo custom
            unset($data['id_materi']);
            unset($data['id_user']);

            $no_id = $this->model_materi->insert_new_custom_pembahasan($data);
        } else {
            // berarti pembahasan umum
            $no_id = $this->model_materi->insert_new_pembahasan($data);
        }

        $result['status'] = 'valid';
        $result['message'] = 'pembahasan berhasil ditambahkan!';
        $result['data'] = $no_id;

        echo json_encode($result);
    }

    public function saldo_topup()
    {
        $nominal = $this->request->getPost('nominal');
        $username = $this->request->getPost('username');

        $data_user = $this->model_user->get_by_username($username);

        $balance = $data_user->balance;
        $id_user = $data_user->id;

        $uang_after = $balance + $nominal;

        $data = array(
            'nominal' => $nominal,
            'status' => 'pending',
            'saldo_sebelum' => $balance,
            'saldo_setelah' => $uang_after,
            'jenis'   => 'isi saldo',
            'id_user' => $id_user,
            'keterangan' => 'Topup saldo oleh user sendiri'
        );

        // 1. Validasi Input
        $validation = \Config\Services::validation();
        $validation->setRules([
            // ID transaksi/pembelian (asumsi hidden input di form)
            // 'id_transaksi' => 'required|integer', 
            'bukti_pembayaran' => [
                'rules' => 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,1024]|is_image[bukti_pembayaran]|mime_in[bukti_pembayaran,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Anda harus mengunggah bukti pembayaran.',
                    'max_size' => 'Ukuran file terlalu besar (maks 1MB).',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in' => 'Format file tidak didukung (gunakan JPG, JPEG, atau PNG).',
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors()
            ]);
        }

        // 2. Proses Upload File
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');

        if ($buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
            // Tentukan nama file baru secara unik
            $newName = $buktiPembayaran->getRandomName();

            // LOKASI BARU: Tentukan direktori tujuan
            // `ROOTPATH` mengarah ke root folder project (portal-fgroupindonesia\)
            // Path lengkap: portal-fgroupindonesia\public\assets\attachment\uploads\payment
            $uploadPath = ROOTPATH . 'public/assets/attachment/uploads/payment';

            // Pindahkan file ke folder yang diminta
            $buktiPembayaran->move($uploadPath, $newName);

            // Path yang akan disimpan di database (untuk diakses melalui web)
            $filePath = 'assets/attachment/uploads/payment/' . $newName;

            // 3. Simpan Data ke Database
            // -- Lakukan proses update status transaksi dan simpan nama file bukti ke database di sini --
            // Contoh: $pembelianModel->update($id_transaksi, ['status' => 'menunggu_verifikasi', 'bukti_bayar_path' => $filePath]);
            $this->model_history_saldo->insert($data);


            // 4. Berikan Respon Sukses
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi.',
                'file_path' => $filePath
            ]);
        } else {
            // Jika ada masalah lain dalam upload (jarang terjadi jika validasi sudah ketat)
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengunggah file bukti pembayaran.'
            ]);
        }
    }

    public function materi_custom_delete()
    {
        $result = ['status' => 'error'];

        // Cek apakah request mengirim array ids (untuk multiple delete)
        $ids = $this->request->getPost('ids');
        if (!empty($ids) && is_array($ids)) {
            $allDeleted = true;
            foreach ($ids as $id) {
                if (!$this->model_materi->delete_custom_existing($id)) {
                    $allDeleted = false;
                }
            }
            if ($allDeleted) {
                $result['status'] = 'success';
            }
        } else {
            // Single delete
            $id = $this->request->getPost('id');
            if ($id && $this->model_materi->delete_custom_existing($id)) {
                $result['status'] = 'success';
            }
        }

        echo json_encode($result);
    }

    public function materi_custom_edit()
    {

        $id          = $this->request->getPost('id');

        $filter = array(
            'id' => $id
        );

        $returned_value = $this->model_materi->get_custom_by($filter);

        $result = array(
            'status' => 'error'
        );

        if ($returned_value) {
            $result['status'] = 'success';
            $result['data'] = $returned_value;
        }

        echo json_encode($result);
    }

    public function materi_custom_update()
    {

        $result = array(
            'status' => 'error'
        );

        $id = $this->request->getPost('id');
        $nt = $this->request->getPost('nama_template');
        $d = $this->request->getPost('deskripsi');

        $data = array(
            'nama_template' => $nt,
            'deskripsi' => $d
        );


        $this->model_materi->update_custom_existing($data, $id);

        $result['status'] = 'success';

        echo json_encode($result);
    }

    public function materi_custom_add()
    {

        $result = array(
            'status' => 'error'
        );

        $id = $this->request->getPost('id_materi');
        $nt = $this->request->getPost('nama_template');
        $d = $this->request->getPost('deskripsi');

        $data = array(
            'id_materi' => $id,
            'nama_template' => $nt,
            'deskripsi' => $d
        );


        $this->model_materi->insert_custom_new($data);

        $result['status'] = 'success';

        echo json_encode($result);
    }

    public function materi_add()
    {

        $result = array(
            'status' => 'invalid'
        );

        $j = $this->request->getPost('judul');
        $k = $this->request->getPost('kategori');
        $d = $this->request->getPost('deskripsi');
        $a = $this->request->getPost('attachment');
        $i = $this->request->getPost('icon');
        $u = $this->request->getPost('username');

        $data = array(
            'judul' => $j,
            'kategori' => $k,
            'deskripsi' => $d,
            'icon' => $i,
            'username' => $u,
            'url' => url_title($j, '-', true)
        );

        if (!empty($a)) {
            $data['attachment'] = $a;
        }

        $this->model_materi->insert_new($data);

        $result['status'] = 'valid';

        echo json_encode($result);
    }

    public function materi_update()
    {

        $result = array(
            'status' => 'invalid'
        );

        $j = $this->request->getPost('judul');
        $k = $this->request->getPost('kategori');
        $d = $this->request->getPost('deskripsi');
        $a = $this->request->getPost('attachment');
        $i = $this->request->getPost('icon');
        $u = $this->request->getPost('username');

        $id             = $this->request->getPost('id');

        $data = array(
            'judul' => $j,
            'kategori' => $k,
            'deskripsi' => $d,
            'attachment' => $a,
            'icon' => $i,
            'username' => $u
        );

        $result = $this->model_materi->update_existing($data, $id);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function materi_kategori_delete()
    {

        $result = array(
            'status' => 'invalid'
        );

        $u = $this->request->getPost('username');
        $k = $this->request->getPost('kategori');

        $data = array(
            'username' => $u,
            'kategori' => $k
        );

        $this->model_materi->delete_existing_where_kategori($data);

        $result['status'] = 'valid';

        echo json_encode($result);
    }

    public function materi_icon_add()
    {

        $validationRule = [
            'icon' => [
                'rules' => [
                    'uploaded[icon]',
                    'is_image[icon]',
                    'mime_in[icon,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[icon,2048]'
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            $result = array(
                'status' => 'invalid'
            );

            echo json_encode($result);

            return;
        }

        $icon         = $this->request->getFile('icon');

        if ($icon->isValid() && !$icon->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $icon->getRandomName();
            //$propic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $icon->move(FCPATH . '/assets/img/uploads/materi', $newName);

            // resize the file image dimension right away
            $image = \Config\Services::image();
            $image->withFile(FCPATH . '/assets/img/uploads/materi/' . $newName)
                ->resize(128, 128, false)
                ->save(FCPATH . '/assets/img/uploads/materi/' . $newName);

            $datana = array(
                'icon' => $newName
            );

            $result = array(
                'status' => 'valid',
                'filename' => $newName
            );

            echo json_encode($result);
        }
    }

    public function materi_attachment_add()
    {

        $validationRule = [
            'attachment' => [
                'rules' => [
                    'uploaded[attachment]',
                    'mime_in[attachment,' .
                        'application/pdf,' .
                        'application/msword,' .
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document,' .
                        'application/vnd.ms-excel,' .
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,' .
                        'text/plain,' .
                        // 👇 TAMBAHAN UNTUK GAMBAR
                        'image/jpeg,' .
                        'image/png' .
                        ']',
                    'max_size[attachment,2048]', // 2MB
                    'ext_in[attachment,pdf,doc,docx,xls,xlsx,txt,' .
                        // 👇 TAMBAHAN UNTUK GAMBAR
                        'jpg,jpeg,png' .
                        ']'
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            $result = array(
                'status' => 'invalid'
            );

            echo json_encode($result);

            return;
        }

        $attachment         = $this->request->getFile('attachment');

        if ($attachment->isValid() && !$attachment->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $attachment->getRandomName();
            //$propic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $attachment->move(FCPATH . '/assets/attachment/uploads/materi', $newName);

            $datana = array(
                'attachment' => $newName
            );

            $result = array(
                'status' => 'valid',
                'filename' => $newName
            );

            echo json_encode($result);
        }
    }

    public function logout()
    {

        $this->session->destroy();

        return redirect()->to('/');
    }

    public function materi_kategori_add()
    {

        $us     = $this->request->getPost('username');
        $kat    = $this->request->getPost('kategori');

        $data = array(
            'username' => $us,
            'kategori' => $kat
        );

        $returned_result = $this->model_materi->insert_new_kategori($data);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_result) {
            $result['status'] = 'valid';
        }

        echo json_encode($result);
    }

    public function materi_kategori_all()
    {

        $us = $this->request->getPost('username');

        $returned_result = $this->model_materi->get_all_kategori($us);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_result) {
            $result['status'] = 'valid';
            $result['data'] = $returned_result;
        }

        echo json_encode($result);
    }



    public function user_add()
    {


        $nama_lengkap   = $this->request->getPost('nama_lengkap');
        $usertype       = $this->request->getPost('usertype');
        $username       = $this->request->getPost('username');
        $email          = $this->request->getPost('email');
        $pass           = $this->request->getPost('pass');
        $wa             = $this->request->getPost('whatsapp');
        $gender             = $this->request->getPost('gender');
        $email_notification             = $this->request->getPost('email_notification');

        //echo var_dump($email_notification);


        $data = array(
            'nama_lengkap' => $nama_lengkap,
            'username'  => $username,
            'email' => $email,
            'pass' => $pass,
            'whatsapp' => $wa,
            'gender' => $gender,
            'usertype' => $usertype
        );

        $validationRule = [
            'propic' => [
                'rules' => [
                    'uploaded[propic]',
                    'is_image[propic]',
                    'mime_in[propic,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[propic,2048]'
                ],
            ],
        ];

        if ($this->validate($validationRule)) {

            $propic         = $this->request->getFile('propic');

            // a propic image
            if ($propic->isValid() && !$propic->hasMoved()) {
                // Move the file to the writable/uploads directory
                $newName = $propic->getRandomName();
                //$propic->move(WRITEPATH . '/uploads', $newName);

                // move to another location
                $propic->move(FCPATH . '/assets/img/uploads/propic', $newName);

                $data['propic'] = $newName;
            }
        }

        $result = $this->model_user->insert_new($data);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";

            if (!empty($email_notification)) {
                $data_email = array(
                    'link_portal' => base_url(),
                    'customer_name' => $nama_lengkap,
                    'customer_email' => $email,
                    'temporary_password' => $pass,
                    'link_logo' => $this->link_logo
                );

                $emailSender = new EmailPostSender();

                $html_client = view('email/client/registration_success', $data_email, ['save' => true]);

                // kirim dulu ke client
                $result = $emailSender->sendPost($html_client, $email, null, 'Akun Anda Berhasil Dibuat!', null);

                $end_result['email_status'] = $result;
            }
        }

        echo json_encode($end_result);
    }

    public function user_delete()
    {

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_user->delete_existing($id);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
        }

        echo json_encode($result);
    }

    public function user_edit()
    {

        $id          = $this->request->getPost('id');

        $filter = array(
            'id' => $id
        );

        $returned_value = $this->model_user->get_by($filter);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
        }

        echo json_encode($result);
    }

    public function user_delete_propic()
    {

        // we reset back the propic to default

        $id          = $this->request->getPost('id');

        $datana = array(
            'propic' => 'empty.png'
        );

        $this->model_user->update_existing($datana, $id);

        $result = array(
            'status' => 'valid'
        );

        echo json_encode($result);
    }

    public function user_update_propic()
    {

        $validationRule = [
            'propic' => [
                'rules' => [
                    'uploaded[propic]',
                    'is_image[propic]',
                    'mime_in[propic,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[propic,2048]'
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
            $result = array(
                'status' => 'invalid'
            );

            echo json_encode($result);

            return;
        }

        $id             = $this->request->getPost('id');
        $propic         = $this->request->getFile('propic');

        if ($propic->isValid() && !$propic->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $propic->getRandomName();
            //$propic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $propic->move(FCPATH . '/assets/img/uploads/propic', $newName);

            $datana = array(
                'propic' => $newName
            );

            $this->model_user->update_existing($datana, $id);

            $result = array(
                'status' => 'valid',
                'filename' => $newName
            );

            echo json_encode($result);
        }
    }

    // this is opened by get Request 
    // to reinforce the Session data
    public function reinforce_user_settings()
    {

        $us = $this->request->getGet('username');

        $data = $this->model_user->get_by_username($us);

        $this->save_again_session($data);

        header('Location: /homepage');
        exit; // Always call exit after header redirection

    }

    private function save_again_session($data_user)
    {

        $this->session->set('propic', $data_user->propic);
        $this->session->set('nama_lengkap', $data_user->nama_lengkap);
        $this->session->set('email', $data_user->email);
        $this->session->set('pass', $data_user->pass);
        $this->session->set('whatsapp', $data_user->whatsapp);
        $this->session->set('username', $data_user->username);
    }

    public function user_update()
    {

        $nama_lengkap   = $this->request->getPost('nama_lengkap');

        $username       = $this->request->getPost('username');
        $email          = $this->request->getPost('email');
        $pass           = $this->request->getPost('pass');
        $wa             = $this->request->getPost('whatsapp');
        $gender             = $this->request->getPost('gender');
        $id             = $this->request->getPost('id');

        $data = array(
            'nama_lengkap' => $nama_lengkap,
            'username'  => $username,
            'email' => $email,
            'pass' => $pass,
            'gender' => $gender,
            'whatsapp' => $wa
        );

        $result = $this->model_user->update_existing($data, $id);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }


    public function group_diskusi_update()
    {

        $n   = $this->request->getPost('nama');
        $url = $this->request->getPost('url');
        $j   = $this->request->getPost('jenis');
        $u   = $this->request->getPost('username');
        $id             = $this->request->getPost('id');

        $data = array(
            'username' => $u,
            'url'  => $url,
            'jenis' => $j,
            'nama' => $n
        );


        $result = $this->model_group_diskusi->update_existing($data, $id);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function group_diskusi_delete()
    {

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_group_diskusi->delete_existing($id);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
        }

        echo json_encode($result);
    }

    public function group_diskusi_edit()
    {

        $id          = $this->request->getPost('id');

        $filter = array(
            'id' => $id
        );

        $returned_value = $this->model_group_diskusi->get_by($filter);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
        }

        echo json_encode($result);
    }

    public function group_diskusi_add()
    {

        $n   = $this->request->getPost('nama');
        $url = $this->request->getPost('url');
        $j   = $this->request->getPost('jenis');
        $u   = $this->request->getPost('username');

        $data = array(
            'username' => $u,
            'url'  => $url,
            'jenis' => $j,
            'nama' => $n
        );

        //echo json_encode($data);

        $result = $this->model_group_diskusi->insert_new($data);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }



    public function info_afiliasi_update()
    {

        $j   = $this->request->getPost('judul');
        $b       = $this->request->getPost('berita');
        $s          = $this->request->getPost('status');
        $id             = $this->request->getPost('id');

        $data = array(
            'judul' => $j,
            'berita'  => $b,
            'status' => $s
        );

        $result = $this->model_info_afiliasi->update_existing($data, $id);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function customer_services_reset()
    {

        // this is for resetting all account
        // coz we will got the array values here
        $ids = $this->request->getPost('id');

        foreach ($ids as $id) {

            $data = array(
                'whatsapp' => null,
                'nama'  => null
            );

            $result = $this->model_customer_services->update_existing($data, $id);
        }

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function customer_services_list()
    {

        $datana = $this->model_customer_services->get_all();

        $data = array(
            'status' => 'invalid',
            'data' => $datana
        );


        if ($datana) {
            $data['status'] = 'valid';
        }

        echo json_encode($data);
    }

    public function customer_services_update()
    {

        $wa           = $this->request->getPost('whatsapp');
        $nama         = $this->request->getPost('nama');
        $status         = $this->request->getPost('status');
        $ids           = $this->request->getPost('id');

        for ($n = 0; $n < sizeof($ids); $n++) {
            $id = $ids[$n];

            $data = array(
                'nama' => $nama[$n],
                'whatsapp'  => $wa[$n],
                'status'  => $status[$n],
            );

            $result = $this->model_customer_services->update_existing($data, $id);
        }




        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function info_afiliasi_edit()
    {

        $id          = $this->request->getPost('id');

        $filter = array(
            'id' => $id
        );

        $returned_value = $this->model_info_afiliasi->get_by($filter);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
        }

        echo json_encode($result);
    }

    public function info_afiliasi_delete()
    {

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_info_afiliasi->delete_existing($id);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
        }

        echo json_encode($result);
    }

    public function info_afiliasi_add()
    {


        $j   = $this->request->getPost('judul');
        $b   = $this->request->getPost('berita');
        $s   = $this->request->getPost('status');

        $data = array(
            'judul'     => $j,
            'berita'    => $b,
            'status'    => $s
        );

        $result = $this->model_info_afiliasi->insert_new($data);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function program_afiliasi_delete()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }
        $id = $this->request->getPost('id');
        if ($id) {
            // Hapus pivot (jika tidak ada cascade)
            //model('ProgramAfiliasiKategoriModel')->where('id_program', $id)->delete();
            $this->model_program_afiliasi->delete($id);
            return $this->response->setJSON(['status' => 'success']);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'ID tidak valid']);
    }

    public function program_afiliasi_edit($id)
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $data = $this->model_program_afiliasi->find($id);
        if ($data) {
            // Ambil kategori dan komisi
            $pakModel = $this->model_program_afiliasi_kategori;
            $kategori = $pakModel->where('id_program', $id)->findAll();
            $data['kategori'] = $kategori; // array dengan id_kategori dan komisi_persen
            return $this->response->setJSON(['status' => 'success', 'data' => $data]);
        }
        return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak ditemukan']);
    }


    public function program_afiliasi_add()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $nama = trim($this->request->getPost('nama'));
        $deskripsi = trim($this->request->getPost('deskripsi'));
        $kategori_ids = $this->request->getPost('kategori_ids');
        $komisi_persens = $this->request->getPost('komisi_persens');

        if (empty($nama)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Nama harus diisi']);
        }

        $data = [
            'nama' => $nama,
            'deskripsi' => $deskripsi,
            'total_member' => 0,
            'status' => 'active',
            'created_by' => session()->get('id_user')
        ];

        // Upload icon
        $file = $this->request->getFile('icon');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/uploads/afiliasi', $newName);
            $data['icon'] = $newName;
        }

        $this->model_program_afiliasi->insert($data);
        $id_program = $this->model_program_afiliasi->insertID();

        // Simpan kategori & komisi
        if ($id_program && !empty($kategori_ids)) {
            $pakModel = $this->model_program_afiliasi_kategori;
            foreach ($kategori_ids as $index => $id_kategori) {
                $komisi = isset($komisi_persens[$index]) ? (int)$komisi_persens[$index] : 0;
                if ($id_kategori > 0) {
                    $pakModel->insert([
                        'id_program' => $id_program,
                        'id_kategori' => $id_kategori,
                        'komisi_persen' => $komisi
                    ]);
                }
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Program berhasil ditambahkan']);
    }

    public function program_afiliasi_update()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id = $this->request->getPost('id');
        $nama = trim($this->request->getPost('nama'));
        $deskripsi = trim($this->request->getPost('deskripsi'));
        $kategori_ids = $this->request->getPost('kategori_ids');
        $komisi_persens = $this->request->getPost('komisi_persens');
        $delete_icon = $this->request->getPost('delete_icon');

        if (empty($id) || empty($nama)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        $data = ['nama' => $nama, 'deskripsi' => $deskripsi];

        // Upload icon jika ada
        $file = $this->request->getFile('icon');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $old = $this->model_program_afiliasi->find($id);
            if ($old && !empty($old['icon']) && file_exists(FCPATH . 'assets/img/uploads/afiliasi/' . $old['icon'])) {
                unlink(FCPATH . 'assets/img/uploads/afiliasi/' . $old['icon']);
            }
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'assets/img/uploads/afiliasi', $newName);
            $data['icon'] = $newName;
        } elseif ($delete_icon == '1') {
            // Hapus icon
            $old = $this->model_program_afiliasi->find($id);
            if ($old && !empty($old['icon']) && file_exists(FCPATH . 'assets/img/uploads/afiliasi/' . $old['icon'])) {
                unlink(FCPATH . 'assets/img/uploads/afiliasi/' . $old['icon']);
            }
            $data['icon'] = 'question.png';
        }

        $this->model_program_afiliasi->update($id, $data);

        // Update kategori & komisi (hapus lama, insert ulang)
        $pakModel = $this->model_program_afiliasi_kategori;
        $pakModel->where('id_program', $id)->delete();

        if (!empty($kategori_ids)) {
            foreach ($kategori_ids as $index => $id_kategori) {
                $komisi = isset($komisi_persens[$index]) ? (int)$komisi_persens[$index] : 0;
                if ($id_kategori > 0) {
                    $pakModel->insert([
                        'id_program' => $id,
                        'id_kategori' => $id_kategori,
                        'komisi_persen' => $komisi
                    ]);
                }
            }
        }

        return $this->response->setJSON(['status' => 'success', 'message' => 'Program berhasil diupdate']);
    }


    public function perangkat_tautan_add()
    {


        $n   = $this->request->getPost('nama');
        $d       = $this->request->getPost('deskripsi');
        $u       = $this->request->getPost('url');
        $idm     = $this->request->getPost('id_materi');

        $result = false;

        if (isset($idm)) {
            foreach ($idm as $s_data) {
                $data = array(
                    'nama' => $n,
                    'deskripsi'  => $d,
                    'url' => $u,
                    'id_materi' => $s_data
                );

                $result = $this->model_perangkat_tautan->insert_new($data);
            }
        } else {

            // submit data without id materi
            $data = array(
                'nama' => $n,
                'deskripsi'  => $d,
                'url' => $u,
                'id_materi' => -1
            );

            $result = $this->model_perangkat_tautan->insert_new($data);
        }

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }


    public function perangkat_tautan_edit()
    {

        $id  = $this->request->getPost('id');

        $filter = array(
            'id' => $id
        );

        $returned_value = $this->model_perangkat_tautan->get_by($filter);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
        }

        echo json_encode($result);
    }

    public function perangkat_tautan_delete()
    {

        $id             = $this->request->getPost('id');

        $returned_value = $this->model_perangkat_tautan->delete_existing($id);

        $result = array(
            'status' => 'invalid'
        );

        if ($returned_value) {
            $result['status'] = 'valid';
        }

        echo json_encode($result);
    }

    public function perangkat_tautan_update()
    {

        $n   = $this->request->getPost('nama');
        $d       = $this->request->getPost('deskripsi');
        $u       = $this->request->getPost('url');
        $idm          = $this->request->getPost('id_materi');
        $id             = $this->request->getPost('id');

        $data = array(
            'nama' => $n,
            'deskripsi'  => $d,
            'url' => $u,
            'id_materi' => $idm
        );


        $result = $this->model_perangkat_tautan->update_existing($data, $id);

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
        }

        echo json_encode($end_result);
    }

    public function perangkat_tautan_browse_materi()
    {

        $u   = $this->request->getPost('username');

        if ($u == 'admin') {
            $result = $this->model_materi->get_all();
        } else {
            $result = $this->model_materi->get_all($u);
        }

        $end_result = array(
            'status' => 'invalid'
        );

        if (!$result) {
            $end_result['status'] = "invalid";
        } else {
            $end_result['status'] = "valid";
            $end_result['data'] = $result;
        }

        echo json_encode($end_result);
    }

    public function generate_chart_user_gender()
    {


        // gender either male / female
        //$gender = $this->request->getGet('gender');
        $gender = $this->request->getPost('gender');

        // we need months name
        $months = [
            "Jan" => 0,
            "Feb" => 0,
            "Mar" => 0,
            "Apr" => 0,
            "May" => 0,
            "Jun" => 0,
            "Jul" => 0,
            "Aug" => 0,
            "Sep" => 0,
            "Oct" => 0,
            "Nov" => 0,
            "Des" => 0
        ];

        if (isset($gender)) {

            $filter = array(
                'gender' => $gender
            );

            $data_all_user = $this->model_user->get_all_by($filter);
        } else {

            $data_all_user = $this->model_user->get_all();
        }

        foreach ($data_all_user as $entry) {
            $monthIndex = date("n", strtotime($entry->date_created)) - 1;
            $monthNames = array_keys($months);
            $months[$monthNames[$monthIndex]]++;
        }

        $data_final = [];

        foreach ($months as $month => $count) {
            $object = array();
            $object['bulan'] = $month;
            $object['jumlah'] = $count;
            $data_final[] = (object) $object;
        }

        echo json_encode($data_final);
    }
}
