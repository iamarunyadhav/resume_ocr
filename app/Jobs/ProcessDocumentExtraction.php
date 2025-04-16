<?php

namespace App\Jobs;

use App\Models\Document;
use App\Services\DocumentExtractor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessDocumentExtraction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle(DocumentExtractor $extractor)
    {
        try {
            Log::info("Starting document extraction for document ID: {$this->document->id}");

            $extractor->process($this->document);

            Log::info("Successfully processed document ID: {$this->document->id}");
        } catch (\Exception $e) {
            Log::error("Document processing failed for ID: {$this->document->id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
