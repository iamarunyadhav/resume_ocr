<?php
namespace App\Services;

use App\Models\Document;
use App\Services\OCR\OCRServiceInterface;
use App\Services\AI\AIServiceInterface;

class DocumentExtractor
{
    public function __construct(
        protected OCRServiceInterface $ocr,
        protected AIServiceInterface $ai,
    ) {}

    public function process(Document $document): void
    {
        $rawText = $this->ocr->extractText($document->path);
        $jsonData = $this->ai->extractFromText($rawText);
        //  dd("ra",$rawText,"jdon",$jsonData);
        $document->update([
            'extracted_data' => $jsonData,
        ]);
    }
}
?>
