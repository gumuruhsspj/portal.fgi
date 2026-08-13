<?php

namespace App\Controllers;

use App\Models\DocumentSealModel;
use App\Services\DocumentSealerService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

class DocumentSealer extends Controller
{
    protected $sealService;
    protected $sealModel;

    public function __construct()
    {
        $this->sealService = new DocumentSealerService();
        $this->sealModel   = new DocumentSealModel();
    }

    /**
     * Tampilkan halaman upload
     */
    public function index()
    {
        return view('upload_seal');
    }

    /**
     * Proses upload, seal, dan simpan
     */
    public function process()
    {
        $file = $this->request->getFile('document');
        $ownerName = $this->request->getPost('owner_name') ?? 'Unknown';

        // Validasi file
        if (!$file || !$file->isValid()) {
            $errorMsg = 'File tidak valid atau tidak diunggah.';
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $errorMsg]);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        // Validasi tipe file yang didukung
        $allowedTypes = ['docx', 'xlsx', 'pptx', 'pdf'];
        $extension = $file->getExtension();
        if (!in_array(strtolower($extension), $allowedTypes)) {
            $errorMsg = 'Tipe file tidak didukung. Gunakan: ' . implode(', ', $allowedTypes);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $errorMsg]);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        // Proses sealing
        try {
            $result = $this->sealService->processFile($file, $ownerName);
        } catch (\Exception $e) {
            $errorMsg = 'Gagal memproses dokumen: ' . $e->getMessage();
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['success' => false, 'message' => $errorMsg]);
            }
            return redirect()->back()->with('error', $errorMsg);
        }

        // Simpan ke database
        $data = [
            'document_id'   => $result['document_id'],
            'user_id'       => null,
            'owner_name'    => $ownerName,
            'original_file' => $result['original_path'],
            'sealed_file'   => $result['sealed_path'],
            'file_type'     => $extension,
            'file_size'     => $result['file_size'],
            'qr_path'       => $result['qr_path'],
            'status'        => 'sealed',
        ];

        $this->sealModel->insert($data);

        // Jika AJAX, kirim JSON agar client redirect
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success'     => true,
                'document_id' => $result['document_id'],
                'redirect'    => site_url('result/' . $result['document_id'])
            ]);
        }

        // Non-AJAX: redirect biasa
        return redirect()->to('/result/' . $result['document_id']);
    }

    /**
     * Halaman hasil dengan link download
     */
    public function result($documentId)
    {
        $record = $this->sealModel->getByDocumentId($documentId);
        if (!$record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan');
        }

        $data = [
            'document_id'   => $record['document_id'],
            'owner_name'    => $record['owner_name'],
            'file_type'     => $record['file_type'],
            'sealed_file'   => $record['sealed_file'],
            'qr_path'       => $record['qr_path'],
            'created_at'    => $record['created_at'],
        ];

        return view('result_seal', $data);
    }

    /**
     * Download file yang sudah di-seal
     */
    public function download($documentId)
    {
        $record = $this->sealModel->getByDocumentId($documentId);
        if (!$record) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan');
        }

        $filePath = WRITEPATH . 'uploads/sealed/' . $record['sealed_file'];
        if (!file_exists($filePath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File tidak ditemukan');
        }

        return $this->response->download($filePath, null)->setFileName($record['sealed_file']);
    }
}
