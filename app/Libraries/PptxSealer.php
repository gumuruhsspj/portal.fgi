<?php

namespace App\Libraries;

use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\IOFactory;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\Shape\RichText;
use PhpOffice\PhpPresentation\Shape\Drawing\Gd;

class PptxSealer
{
    /**
     * Seal PPTX file with watermark, info, and QR code.
     */
    public function seal(string $inputPath, string $outputPath, string $docId, string $owner, string $qrPath): void
    {
        $presentation = IOFactory::load($inputPath);

        $slides = $presentation->getAllSlides();
        if (empty($slides)) {
            $slide = $presentation->createSlide();
            $slides = [$slide];
        }

        foreach ($slides as $slide) {
            $this->addWatermark($slide);
            $this->addInfoText($slide, $docId, $owner);
        }

        $firstSlide = $slides[0];
        if (file_exists($qrPath)) {
            $this->addQrCode($firstSlide, $qrPath);
        }

        // Simpan file hasil sealing
        $writer = IOFactory::createWriter($presentation, 'PowerPoint2007');
        $writer->save($outputPath);

        // Bersihkan memori
        unset($presentation);
    }

    /**
     * Add "VERIFIED" watermark (large, rotated, light gray).
     */
    protected function addWatermark($slide): void
    {
        $richText = new RichText();
        $richText->setWidth(800);
        $richText->setHeight(200);
        $richText->setOffsetX(100);
        $richText->setOffsetY(250);
        $richText->setRotation(45);

        $paragraph = $richText->createParagraph();
        $paragraph->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $paragraph->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $color = new Color('FFE0E0E0');
        $fontStyle = [
            'size'  => 90,
            'bold'  => true,
            'color' => $color,
        ];

        $paragraph->createText('VERIFIED', $fontStyle);

        $slide->addShape($richText);
    }

    /**
     * Add small info text at bottom of each slide.
     */
    protected function addInfoText($slide, string $docId, string $owner): void
    {
        $infoText = sprintf('Doc ID: %s | Owner: %s | Date: %s', $docId, $owner, date('Y-m-d H:i:s'));

        $richText = new RichText();
        $richText->setWidth(900);
        $richText->setHeight(30);
        $richText->setOffsetX(10);
        $richText->setOffsetY(530);

        $paragraph = $richText->createParagraph();
        $paragraph->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
        $paragraph->getAlignment()->setVertical(Alignment::VERTICAL_BOTTOM);

        $color = new Color('FF666666');
        $fontStyle = [
            'size'  => 10,
            'color' => $color,
        ];

        $paragraph->createText($infoText, $fontStyle);

        $slide->addShape($richText);
    }

    /**
     * Add QR code image (top-right corner).
     */
    protected function addQrCode($slide, string $qrPath): void
    {
        $image = new Gd();
        $image->setPath($qrPath);
        $image->setWidth(120);
        $image->setHeight(120);
        $image->setOffsetX(750);
        $image->setOffsetY(10);
        $slide->addShape($image);
    }
}
