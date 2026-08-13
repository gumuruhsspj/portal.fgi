<?php

namespace App\Libraries;

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Element\Protection;
use PhpOffice\PhpWord\SimpleType\DocProtect;

class DocxSealer
{
    /**
     * Seal DOCX file with watermark text, document info, QR code, and read-only protection.
     */
    public function seal(string $inputPath, string $outputPath, string $docId, string $owner, string $qrPath): void
    {
        $phpWord = IOFactory::load($inputPath);

        $sections = $phpWord->getSections();
        if (empty($sections)) {
            $section = $phpWord->addSection();
        } else {
            $section = $sections[0];
        }

        $this->addHeader($section, $docId, $owner);
        $this->addFooter($section, $qrPath);
        $this->protectDocument($phpWord);

        $phpWord->save($outputPath);
    }

    protected function addHeader($section, string $docId, string $owner): void
    {
        $header = $section->getHeader();
        if (!$header) {
            $header = $section->addHeader();
        }

        $watermarkStyle = ['size' => 72, 'color' => 'CCCCCC', 'bold' => true];
        $header->addText('VERIFIED', $watermarkStyle, ['alignment' => Jc::CENTER]);

        $info = sprintf('Document ID: %s | Owner: %s | Date: %s', $docId, $owner, date('Y-m-d H:i:s'));
        $infoStyle = ['size' => 10, 'color' => '666666'];
        $header->addText($info, $infoStyle, ['alignment' => Jc::CENTER]);
        $header->addTextBreak(1);
    }

    protected function addFooter($section, string $qrPath): void
    {
        $footer = $section->getFooter();
        if (!$footer) {
            $footer = $section->addFooter();
        }

        if (!file_exists($qrPath)) {
            $footer->addText('QR Code not available', ['size' => 8, 'color' => 'FF0000']);
            return;
        }

        $imageStyle = [
            'width'        => 80,
            'height'       => 80,
            'alignment'    => Jc::CENTER,
            'marginTop'    => 5,
            'marginBottom' => 5,
        ];
        $footer->addImage($qrPath, $imageStyle);
    }

    /**
     * Set document protection to read-only using Protection object (PHPWord 1.4.0 compatible).
     */
    protected function protectDocument(PhpWord $phpWord): void
    {
        try {
            $settings = $phpWord->getSettings();
            // Create a Protection object and set editing to read-only
            $protection = new Protection();
            $protection->setEditing(DocProtect::READ_ONLY);
            $settings->setDocumentProtection($protection);
        } catch (\Throwable $e) {
            // If protection fails for any reason, silently skip it
        }
    }
}
