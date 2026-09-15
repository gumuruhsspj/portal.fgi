<?php

namespace App\Libraries;

require_once ROOTPATH . 'vendor/setasign/fpdf/fpdf.php';

use FPDF;

class CertificateComposer
{
    /**
     * Render 1 sisi jadi JPEG (sudah dikasih overlay text).
     * Return path file temporary.
     */
    public function composeSide(
        string $imagePath,
        int $width,
        int $height,
        array $variables,
        array $values
    ): string {
        // Load image base
        $info = getimagesize($imagePath);
        if (!$info) {
            throw new \RuntimeException('Image tidak valid: ' . $imagePath);
        }

        switch ($info['mime']) {
            case 'image/jpeg':
                $img = imagecreatefromjpeg($imagePath);
                break;
            case 'image/png':
                $img = imagecreatefrompng($imagePath);
                break;
            default:
                throw new \RuntimeException('Format image tidak didukung: ' . $info['mime']);
        }

        // Overlay tiap variable
        foreach ($variables as $v) {
            $text = $this->resolveValue($v, $values);
            if ($text === '') continue;

            $x         = (int) ($v['x'] ?? 0);
            $y         = (int) ($v['y'] ?? 0);
            $fontSize  = (int) ($v['font_size'] ?? 24);
            $colorHex  = $v['color'] ?? '#000000';

            $fontFile  = $this->resolveFont(
                $v['font'] ?? 'arial',
                $v['font_style'] ?? 'regular'
            );

            [$r, $g, $b] = $this->hexToRgb($colorHex);
            $color = imagecolorallocate($img, $r, $g, $b);

            if (file_exists($fontFile)) {
                imagettftext($img, $fontSize, 0, $x, $y, $color, $fontFile, $text);
            } else {
                // Fallback built-in font (jika ttf tidak ada)
                imagestring($img, 5, $x, $y - 15, $text, $color);
            }
        }

        // Simpan JPEG kualitas tinggi (biar size tetap dan file tidak membengkak)
        $tmp = tempnam(sys_get_temp_dir(), 'cert_') . '.jpg';
        imagejpeg($img, $tmp, 100); // kualitas 100 = mendekati lossless
        imagedestroy($img);

        return $tmp;
    }

    /**
     * Gabungkan front + (optional) back ke 1 PDF.
     * $frontPath & $backPath = path hasil composeSide()
     * $frontW/$frontH = ukuran pixel asli (fixed)
     */
    public function buildPdf(
        string $frontPath,
        int $frontW,
        int $frontH,
        ?string $backPath = null,
        ?int $backW = null,
        ?int $backH = null,
        ?string $title = null           // <-- TAMBAHAN
    ): string {
        $fi = @getimagesize($frontPath);
        if ($fi) {
            $frontW = (int) $fi[0];
            $frontH = (int) $fi[1];
        }

        $pdf = new FPDF('P', 'pt', [$frontW, $frontH]);
        $pdf->SetAutoPageBreak(false);
        $pdf->SetMargins(0, 0, 0);

        // Set PDF metadata title
        if (!empty($title)) {
            $pdf->SetTitle($title);
            $pdf->SetAuthor('FGroupIndonesia');
            $pdf->SetCreator('FGroupIndonesia');
        }

        $pdf->AddPage();
        $pdf->Image($frontPath, 0, 0, $frontW, $frontH);

        if ($backPath && $backW && $backH) {
            $bi = @getimagesize($backPath);
            if ($bi) {
                $backW = (int) $bi[0];
                $backH = (int) $bi[1];
            }
            $pdf->AddPage('P', [$backW, $backH]);
            $pdf->Image($backPath, 0, 0, $backW, $backH);
        }

        $pdfTmp = tempnam(sys_get_temp_dir(), 'certpdf_') . '.pdf';
        $pdf->Output('F', $pdfTmp);

        return $pdfTmp;
    }

    private function resolveValue(array $v, array $values): string
    {
        if (($v['type'] ?? 'system') === 'custom') {
            return (string) ($v['value'] ?? '');
        }

        $key = $v['key'] ?? '';
        return (string) ($values[$key] ?? '');
    }

    /**
     * Resolve path file font TTF berdasarkan family + style.
     * $style: 'regular' | 'bold' | 'italic' | 'bold_italic'
     */
    private function resolveFont(string $name, string $style = 'regular'): string
    {
        $familyMap = [
            'arial'   => ['regular' => 'arial',   'bold' => 'arialbd', 'italic' => 'ariali',   'bold_italic' => 'arialbi'],
            'times'   => ['regular' => 'times',   'bold' => 'timesbd', 'italic' => 'timesi',   'bold_italic' => 'timesbi'],
            'courier' => ['regular' => 'cour',    'bold' => 'courbd',  'italic' => 'couri',    'bold_italic' => 'courbi'],
        ];

        $family = $familyMap[$name] ?? $familyMap['arial'];
        $style  = $style ?: 'regular';

        // coba style spesifik dulu
        $candidates = [];
        if (isset($family[$style])) {
            $candidates[] = FCPATH . 'assets/fonts/' . $family[$style] . '.ttf';
        }
        // fallback ke regular family
        $candidates[] = FCPATH . 'assets/fonts/' . $family['regular'] . '.ttf';
        // fallback terakhir
        $candidates[] = FCPATH . 'assets/fonts/arial.ttf';

        foreach ($candidates as $path) {
            if (file_exists($path)) return $path;
        }

        return FCPATH . 'assets/fonts/arial.ttf';
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) !== 6) return [0, 0, 0];
        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
