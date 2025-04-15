<?php
namespace App\Services\OCR;

interface OCRServiceInterface
{
    public function extractText(string $filePath): string;
}
?>
