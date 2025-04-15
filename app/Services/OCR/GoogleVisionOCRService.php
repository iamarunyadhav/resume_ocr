<?php

namespace App\Services\OCR;

use Smalot\PdfParser\Parser as PdfParser;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class GoogleVisionOCRService implements OCRServiceInterface
{
    public function extractText(string $filePath): string
    {
        // Resolve the absolute storage path
        $absolutePath = Storage::disk('public')->path($filePath);

        if (!file_exists($absolutePath)) {
            throw new Exception("OCR file not found at: {$absolutePath}");
        }

        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        return match ($extension) {
            'docx' => $this->extractDocxText($absolutePath),
            'pdf' => $this->extractPdfText($absolutePath),
            default => throw new Exception("Unsupported file format: {$extension}. Only .pdf and .docx are allowed."),
        };
    }

    protected function extractDocxText(string $path): string
    {
        try {
            $phpWord = IOFactory::load($path);
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                $elements = $section->getElements();
                foreach ($elements as $element) {
                    if (method_exists($element, 'getText')) {
                        $text .= $element->getText() . "\n";
                    }
                }
            }

            return trim($text);
        } catch (Exception $e) {
            Log::error("DOCX OCR failed: " . $e->getMessage());
            return '';
        }
    }

    protected function extractPdfText(string $path): string
    {
        try {
            $parser = new PdfParser();
            $pdf = $parser->parseFile($path);
            return trim($pdf->getText());
        } catch (Exception $e) {
            Log::error("PDF OCR failed: " . $e->getMessage());
            return '';
        }
    }
}
