<?php

namespace App\Services\OCR;

use thiagoalessio\TesseractOCR\TesseractOCR;

class TesseractOCRService implements OCRServiceInterface
{
    public function extractText(string $filePath): string
    {
        return (new TesseractOCR(storage_path('app/public/' . $filePath)))
            ->lang('eng')
            ->run();
    }
}
?>
