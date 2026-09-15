<?php

namespace App\Controllers;

use App\Libraries\CertificateComposer;

class Certificate extends BaseController
{
    /* ===================== LIST / ADMIN PAGE ===================== */

    public function index()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        $id_materi = $this->request->getGet('materi_id');

        $data['templates']       = $this->model_materi->get_cert_templates($id_materi) ?: [];
        $data['materi_filter']   = $this->model_materi->get_all() ?: [];
        $data['selected_materi'] = $id_materi;

        $data['link_management_open'] = 'menu-open';
        $data['link_management_certificate_active'] = 'active';
        $data['random'] = '?v=' . time();

        return view('management_certificate', $data);
    }

    /* ===================== EDITOR PAGE ===================== */

    public function editor()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        $id_materi = $this->request->getGet('materi_id');
        $id_tpl    = $this->request->getGet('id'); // optional, kalau edit yg sudah ada

        if (!$id_materi) {
            return redirect()->to('/manage/certificate')->with('error', 'Pilih materi dulu');
        }

        $data_materi = $this->model_materi->get_by(['id' => $id_materi]);
        if (!$data_materi) {
            return redirect()->to('/manage/certificate')->with('error', 'Materi tidak ditemukan');
        }

        // ambil list custom materi untuk paket_kasus_custom
        $data['materi_custom_list'] = $this->model_materi->get_all_custom(null, $id_materi) ?: [];

        $data['data_materi']  = $data_materi;
        $data['id_materi']    = $id_materi;

        // Kalau edit existing
        $tpl = null;
        if ($id_tpl) {
            $tpl = $this->model_materi->get_cert_template_by_id($id_tpl);
        }
        $data['template'] = $tpl;

        $data['link_management_open'] = 'menu-open';
        $data['link_management_certificate_active'] = 'active';
        $data['random'] = '?v=' . time();

        return view('certificate_editor', $data);
    }

    /* ===================== SAVE (create/update) ===================== */

    public function save()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $id         = (int) $this->request->getPost('id');
        $id_materi  = (int) $this->request->getPost('id_materi');
        $paket      = $this->request->getPost('paket');
        $id_custom  = $this->request->getPost('id_custom_materi');
        $nama_tpl   = trim((string) $this->request->getPost('nama_template'));
        $side_mode  = $this->request->getPost('side_mode') === 'double' ? 'double' : 'single';
        $front_vars = $this->request->getPost('front_variables');
        $back_vars  = $this->request->getPost('back_variables');
        $orientation = $this->request->getPost('orientation') ?: 'auto';

        if (!$id_materi || !$paket || $nama_tpl === '') {
            return $this->response->setJSON(['status' => 'invalid', 'message' => 'Data tidak lengkap']);
        }

        if ($paket !== 'paket_kasus_custom') $id_custom = null;

        $data = [
            'id_materi'       => $id_materi,
            'paket'           => $paket,
            'id_custom_materi' => $id_custom ?: null,
            'nama_template'   => $nama_tpl,
            'side_mode'       => $side_mode,
            'orientation'       => $orientation,
            'front_variables' => $front_vars ?: json_encode([]),
            'back_variables'  => $back_vars ?: json_encode([]),
        ];

        // Upload front image
        $ff = $this->request->getFile('front_image');
        if ($ff && $ff->isValid() && !$ff->hasMoved()) {
            $newName = $ff->getRandomName();
            $ff->move(FCPATH . 'assets/img/uploads/certificate', $newName);
            $fullPath = FCPATH . 'assets/img/uploads/certificate/' . $newName;

            [$w, $h] = getimagesize($fullPath);
            $data['front_image']  = 'assets/img/uploads/certificate/' . $newName;
            $data['front_width']  = (int) $w;
            $data['front_height'] = (int) $h;
        }

        // Upload back image (optional)
        $bf = $this->request->getFile('back_image');
        if ($side_mode === 'double' && $bf && $bf->isValid() && !$bf->hasMoved()) {
            $newName = $bf->getRandomName();
            $bf->move(FCPATH . 'assets/img/uploads/certificate', $newName);
            $fullPath = FCPATH . 'assets/img/uploads/certificate/' . $newName;

            [$w, $h] = getimagesize($fullPath);
            $data['back_image']  = 'assets/img/uploads/certificate/' . $newName;
            $data['back_width']  = (int) $w;
            $data['back_height'] = (int) $h;
        }

        if ($id) {
            $ok = $this->model_materi->update_cert_template($data, $id);
            $newId = $id;
        } else {
            // Wajib ada front image untuk template baru
            if (empty($data['front_image'])) {
                return $this->response->setJSON(['status' => 'invalid', 'message' => 'Front image wajib']);
            }
            $newId = $this->model_materi->insert_cert_template($data);
            $ok    = (bool) $newId;
        }

        // ============================================================
        // TAMBAHAN BARU: OTOMATIS RILIS SERTIFIKAT
        // Jika template berhasil disimpan, otomatis ubah status rilis_sertifikat ke 'yes'
        // ============================================================
        if ($ok) {
            $materi = $this->model_materi->get_by(['id' => $id_materi]);
            // Cek jika materi ditemukan dan statusnya masih belum 'yes'
            if ($materi && ($materi->rilis_sertifikat ?? 'no') !== 'yes') {
                // Update status rilis sertifikat menjadi 'yes'
                $this->model_materi->update_existing(['rilis_sertifikat' => 'yes'], $id_materi);
            }
        }
        // ============================================================

        return $this->response->setJSON([
            'status'  => $ok ? 'valid' : 'invalid',
            'message' => $ok ? 'Template tersimpan & Sertifikat Otomatis Dirilis' : 'Gagal menyimpan',
            'id'      => $newId,
        ]);
    }

    /* ===================== LOAD (untuk editor) ===================== */

    public function load()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'error']);

        $id = (int) $this->request->getPost('id');
        $tpl = $this->model_materi->get_cert_template_by_id($id);
        if (!$tpl) return $this->response->setJSON(['status' => 'invalid']);

        // Decode variables
        $tpl->front_variables = json_decode($tpl->front_variables ?: '[]', true) ?: [];
        $tpl->back_variables  = json_decode($tpl->back_variables  ?: '[]', true) ?: [];

        return $this->response->setJSON(['status' => 'valid', 'data' => $tpl]);
    }

    /* ===================== DELETE ===================== */

    public function delete()
    {
        $this->is_logged_in();
        if (!$this->request->isAJAX()) return $this->response->setJSON(['status' => 'error']);

        $id = (int) $this->request->getPost('id');
        if (!$id) return $this->response->setJSON(['status' => 'invalid']);

        $tpl = $this->model_materi->get_cert_template_by_id($id);
        if ($tpl) {
            // hapus file
            if (!empty($tpl->front_image) && file_exists(FCPATH . $tpl->front_image)) {
                @unlink(FCPATH . $tpl->front_image);
            }
            if (!empty($tpl->back_image) && file_exists(FCPATH . $tpl->back_image)) {
                @unlink(FCPATH . $tpl->back_image);
            }
            $this->model_materi->delete_cert_template($id);
        }

        return $this->response->setJSON(['status' => 'valid', 'message' => 'Template dihapus']);
    }

    /* ===================== PREVIEW (render dummy) ===================== */

    public function preview($id)
    {
        $this->is_logged_in();
        $tpl = $this->model_materi->get_cert_template_by_id($id);
        if (!$tpl) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $frontVars = json_decode($tpl->front_variables ?: '[]', true) ?: [];
        $backVars  = json_decode($tpl->back_variables  ?: '[]', true) ?: [];

        $dummyValues = [
            'nama_user'     => 'Contoh Nama Peserta',
            'tanggal_cetak' => date('d F Y'),
            'judul_materi'  => 'Contoh Judul Materi',
            'nilai'         => '88.5',
        ];

        $composer = new CertificateComposer();

        $frontPath = $composer->composeSide(
            FCPATH . $tpl->front_image,
            $tpl->front_width,
            $tpl->front_height,
            $frontVars,
            $dummyValues
        );

        $backPath = null;
        if ($tpl->side_mode === 'double' && $tpl->back_image) {
            $backPath = $composer->composeSide(
                FCPATH . $tpl->back_image,
                $tpl->back_width,
                $tpl->back_height,
                $backVars,
                $dummyValues
            );
        }

        $pdfPath = $composer->buildPdf(
            $frontPath,
            $tpl->front_width,
            $tpl->front_height,
            $backPath,
            $tpl->back_width ?: null,
            $tpl->back_height ?: null
        );

        // Output PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="preview_certificate.pdf"');
        readfile($pdfPath);

        @unlink($frontPath);
        if ($backPath) @unlink($backPath);
        @unlink($pdfPath);
        exit;
    }

    /* ===================== DOWNLOAD (student) ===================== */

    /**
     * Siswa klik tombol download dari dashboard.
     * Fungsi ini generate token (kalau belum ada) lalu redirect ke URL token.
     */
    public function prepare_download($id_materi)
    {
        $this->is_logged_in();
        $id_user = session()->get('id_user');

        $attempt = $this->model_materi->get_quiz_attempt_by_user($id_user, $id_materi);
        if (!$attempt || $attempt->status !== 'graded') {
            return redirect()->to('/homepage')->with('error', 'Sertifikat belum tersedia.');
        }

        $materi = $this->model_materi->get_by(['id' => $id_materi]);
        if (!$materi || ($materi->rilis_sertifikat ?? 'no') !== 'yes') {
            return redirect()->to('/homepage')->with('error', 'Sertifikat belum dirilis untuk materi ini.');
        }

        $token = $this->model_materi->ensure_certificate_token($attempt->id);
        if (!$token) {
            return redirect()->to('/homepage')->with('error', 'Gagal menyiapkan sertifikat.');
        }

        return redirect()->to('/certificate/download/' . $token);
    }

    /**
     * Endpoint publik yang diakses via token.
     * Verifikasi: token ada, milik user yang login, quiz sudah graded,
     * template tersedia, materi rilis sertifikat.
     */
    public function download($token = null)
    {
        $this->is_logged_in();

        if (!$token) {
            return redirect()->to('/homepage')->with('error', 'Token tidak valid.');
        }

        $attempt = $this->model_materi->get_attempt_by_certificate_token($token);
        if (!$attempt) {
            return redirect()->to('/homepage')->with('error', 'Sertifikat tidak ditemukan.');
        }

        $id_user = session()->get('id_user');

        // 🔐 Kunci utama: attempt harus milik user yang sedang login
        if ((int) $attempt->id_user !== (int) $id_user) {
            return redirect()->to('/homepage')->with('error', 'Akses ditolak.');
        }

        if ($attempt->status !== 'graded') {
            return redirect()->to('/homepage')->with('error', 'Quiz belum dinilai.');
        }

        $materi = $this->model_materi->get_by(['id' => $attempt->id_materi]);
        if (!$materi) {
            return redirect()->to('/homepage')->with('error', 'Materi tidak ditemukan.');
        }

        if (($materi->rilis_sertifikat ?? 'no') !== 'yes') {
            return redirect()->to('/homepage')->with('error', 'Sertifikat belum dirilis untuk materi ini.');
        }

        $tpl = $this->model_materi->find_cert_template_for_attempt($attempt);
        if (!$tpl) {
            return redirect()->to('/homepage')->with('error', 'Template sertifikat belum tersedia untuk paket Anda.');
        }

        $user = $this->model_user->find($id_user);

        $values = [
            'nama_user'     => $user->nama_lengkap ?? '',
            'tanggal_cetak' => date('d F Y'),
            'judul_materi'  => $materi->judul ?? '',
            'nilai'         => number_format((float) $attempt->final_score, 2),
        ];

        $frontVars = json_decode($tpl->front_variables ?: '[]', true) ?: [];
        $backVars  = json_decode($tpl->back_variables  ?: '[]', true) ?: [];

        $composer = new CertificateComposer();

        $frontPath = $composer->composeSide(
            FCPATH . $tpl->front_image,
            $tpl->front_width,
            $tpl->front_height,
            $frontVars,
            $values
        );

        $backPath = null;
        if ($tpl->side_mode === 'double' && $tpl->back_image) {
            $backPath = $composer->composeSide(
                FCPATH . $tpl->back_image,
                $tpl->back_width,
                $tpl->back_height,
                $backVars,
                $values
            );
        }

        // ============================================================
        // PDF TITLE & FILENAME KHUSUS
        // ============================================================
        $pdfTitle = 'Sertifikat ' . ($materi->judul ?? '')
            . ' - ' . ($user->nama_lengkap ?? '');

        $pdfPath = $composer->buildPdf(
            $frontPath,
            $tpl->front_width,
            $tpl->front_height,
            $backPath,
            $tpl->back_width ?: null,
            $tpl->back_height ?: null,
            $pdfTitle                          // <-- kirim title
        );

        // Filename: Sertifikat-{slug-materi}-{token8}.pdf
        $slug   = url_title($materi->judul ?? 'sertifikat', '-', true);
        $suffix = substr($token, 0, 8);
        $filename = 'Sertifikat-' . $slug . '-' . $suffix . '.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($pdfPath));
        readfile($pdfPath);

        @unlink($frontPath);
        if ($backPath) @unlink($backPath);
        @unlink($pdfPath);
        exit;
    }
}
