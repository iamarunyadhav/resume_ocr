<?php
// app/Services/DocumentExtractor.php

namespace App\Services;

use App\Models\Document;
use App\Services\OCR\OCRServiceInterface;
use App\Services\AI\AIServiceInterface;
use Illuminate\Support\Facades\Log;

class DocumentExtractor
{
    public function __construct(
        protected OCRServiceInterface $ocrService,
        protected AIServiceInterface $aiService
    ) {}

    public function process(Document $document): void
    {
        try {
            // Extract raw text from document
            $rawText = $this->ocrService->extractText($document->path);

            // Extract structured data using AI
            $extractedData = $this->aiService->extractFromText($rawText);

            // Save extracted data
            $document->update([
                'extracted_data' => $extractedData,
                'processing_status' => 'completed'
            ]);

        } catch (\Exception $e) {
            Log::error("Document processing failed: " . $e->getMessage());
            $document->update([
                'processing_status' => 'failed',
                'processing_error' => $e->getMessage()
            ]);
        }
    }
}
