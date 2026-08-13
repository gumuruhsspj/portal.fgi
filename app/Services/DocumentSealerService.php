<?php

namespace App\Services;

use CodeIgniter\Files\File;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Color\Color;

class DocumentSealerService
{
    protected $uploadPath;
    protected $sealedPath;
    protected $qrPath;
    protected $baseUrl;

    protected $docxSealer;
    protected $xlsxSealer;
    protected $pptxSealer;
    protected $pdfSealer;

    public function __construct()
    {
        $this->uploadPath = WRITEPATH . 'uploads/original/';
        $this->sealedPath = WRITEPATH . 'uploads/sealed/';
        $this->qrPath     = WRITEPATH . 'uploads/qr/';
        $this->baseUrl    = base_url();

        $this->ensureDirectories();

        $this->docxSealer = null;
        $this->xlsxSealer = null;
        $this->pptxSealer = null;
        $this->pdfSealer  = null;
    }

    public function processFile(File $file, string $ownerName): array
    {
        $documentId = $this->generateDocumentId();

        $originalName = $file->getClientName();
        $extension = strtolower($file->getExtension());
        $fileSize = $file->getSize();

        $originalPath = $this->uploadPath . $documentId . '_' . $originalName;
        $file->move($this->uploadPath, $documentId . '_' . $originalName);

        $qrPath = $this->generateQrCode($documentId, $ownerName);

        $sealedFileName = $this->getSealedFileName($originalName);
        $sealedPath = $this->sealedPath . $sealedFileName;

        $this->applySeal($extension, $originalPath, $sealedPath, $documentId, $ownerName, $qrPath);

        return [
            'document_id'   => $documentId,
            'original_path' => $originalPath,
            'sealed_path'   => $sealedFileName,
            'qr_path'       => $qrPath,
            'file_size'     => $fileSize,
        ];
    }

    protected function generateDocumentId(): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max = strlen($characters) - 1;
        $id = '';
        for ($i = 0; $i < 16; $i++) {
            $id .= $characters[random_int(0, $max)];
        }
        return $id;
    }


    /**
     * Generate QR Code using direct QrCode and PngWriter instances.
     */
    protected function generateQrCode(string $documentId, string $ownerName): string
    {
        // Check availability
        if (!class_exists(\Endroid\QrCode\QrCode::class)) {
            throw new \Exception('Endroid QR Code library not installed. Please run: composer require endroid/qr-code');
        }

        $data = json_encode([
            'doc_id' => $documentId,
            'owner'  => $ownerName,
            'date'   => date('Y-m-d H:i:s'),
            'verify' => $this->baseUrl . '/result/' . $documentId,
        ]);

        $qrFileName = $documentId . '_qr.png';
        $qrFullPath = $this->qrPath . $qrFileName;

        // Direct instantiation compatible with endroid/qr-code v5.x
        $qrCode = \Endroid\QrCode\QrCode::create($data)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize(300)
            ->setMargin(10)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Save output image
        $result->saveToFile($qrFullPath);

        return $qrFileName;
    }

    protected function getSealedFileName(string $originalName): string
    {
        $info = pathinfo($originalName);
        $filename = $info['filename'];
        $extension = $info['extension'] ?? '';
        return $filename . '_VERIFIED.' . $extension;
    }

    protected function applySeal(string $extension, string $inputPath, string $outputPath, string $docId, string $owner, string $qrPath)
    {
        switch ($extension) {
            case 'docx':
                $this->getDocxSealer()->seal($inputPath, $outputPath, $docId, $owner, $qrPath);
                break;
            case 'xlsx':
                $this->getXlsxSealer()->seal($inputPath, $outputPath, $docId, $owner, $qrPath);
                break;
            case 'pptx':
                $this->getPptxSealer()->seal($inputPath, $outputPath, $docId, $owner, $qrPath);
                break;
            case 'pdf':
                $this->getPdfSealer()->seal($inputPath, $outputPath, $docId, $owner, $qrPath);
                break;
            default:
                throw new \Exception('Unsupported file type: ' . $extension);
        }
    }

    protected function getDocxSealer()
    {
        if ($this->docxSealer === null) {
            $this->docxSealer = new \App\Libraries\DocxSealer();
        }
        return $this->docxSealer;
    }

    protected function getXlsxSealer()
    {
        if ($this->xlsxSealer === null) {
            $this->xlsxSealer = new \App\Libraries\XlsxSealer();
        }
        return $this->xlsxSealer;
    }

    protected function getPptxSealer()
    {
        if ($this->pptxSealer === null) {
            $this->pptxSealer = new \App\Libraries\PptxSealer();
        }
        return $this->pptxSealer;
    }

    protected function getPdfSealer()
    {
        if ($this->pdfSealer === null) {
            $this->pdfSealer = new \App\Libraries\PdfSealer();
        }
        return $this->pdfSealer;
    }

    protected function ensureDirectories()
    {
        $dirs = [$this->uploadPath, $this->sealedPath, $this->qrPath];
        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }
}
