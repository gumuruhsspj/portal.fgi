<?php

namespace App\Libraries;

use Mpdf\Mpdf;
use Exception;

class PdfSealer
{
    /**
     * Seal PDF file (supports compressed PDFs natively via mPDF).
     */
    public function seal(string $inputPath, string $outputPath, string $docId, string $owner, string $qrPath): void
    {
        try {
            // Inisialisasi mPDF
            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_left' => 0,
                'margin_right' => 0,
                'margin_top' => 0,
                'margin_bottom' => 0,
            ]);

            // Set Meta Properties
            $mpdf->SetCreator('Document Sealer System');
            $mpdf->SetAuthor($owner);
            $mpdf->SetTitle('Sealed Document - ' . $docId);
            $mpdf->SetSubject('Verified Document');
            $mpdf->SetKeywords('verified, sealed, qr');

            // Set Proteksi dokumen (opsional)
            $mpdf->SetProtection(['print', 'copy']);

            // Hitung total halaman PDF sumber
            $pageCount = $mpdf->setSourceFile($inputPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                if ($i > 1) {
                    $mpdf->AddPage();
                }

                // Import halaman
                $templateId = $mpdf->importPage($i);
                $mpdf->useTemplate($templateId);

                // Tambahkan Watermark, Info Text, dan QR Code
                $this->addOverlayElements($mpdf, $docId, $owner, $qrPath);
            }

            // Simpan ke file output
            $mpdf->Output($outputPath, \Mpdf\Output\Destination::FILE);
        } catch (Exception $e) {
            throw new Exception('Gagal memproses dokumen PDF: ' . $e->getMessage());
        }
    }

    /**
     * Tambahkan elemen stamp/overlay pada halaman aktif
     */
    protected function addOverlayElements(Mpdf $mpdf, string $docId, string $owner, string $qrPath): void
    {
        // 1. Watermark Text "VERIFIED" di Tengah Halaman
        $mpdf->SetWatermarkText('VERIFIED', 0.1);
        $mpdf->showWatermarkText = true;

        // 2. Info Text di Bagian Bawah
        $infoText = sprintf('Doc ID: %s | Owner: %s | Date: %s', $docId, $owner, date('Y-m-d H:i:s'));

        // Menulis text posisi absolut di kiri bawah
        $mpdf->SetFont('helvetica', '', 8);
        $mpdf->SetTextColor(100, 100, 100);
        // WriteHTML atau SetXY
        $htmlFooter = sprintf(
            '<div style="position: absolute; bottom: 10mm; left: 10mm; font-family: sans-serif; font-size: 8pt; color: #666666;">%s</div>',
            htmlspecialchars($infoText)
        );
        $mpdf->WriteHTML($htmlFooter);

        // 3. QR Code di Kanan Atas
        if (file_exists($qrPath)) {
            $htmlQr = sprintf(
                '<div style="position: absolute; top: 10mm; right: 10mm;"><img src="%s" width="100" height="100" /></div>',
                $qrPath
            );
            $mpdf->WriteHTML($htmlQr);
        }
    }
}
