<?php

namespace App\Livewire\Wizard;

use Livewire\Component;
use App\Models\Document;
use App\Services\DocumentExtractor;
use Illuminate\Support\Facades\Log;

class Step2Review extends Component
{
    public Document $document;
    public array $fields = [];
    public bool $processing = false;
    public ?string $error = null;
    public bool $hasData = false;

    public function mount(Document $document, DocumentExtractor $extractor)
    {
        $this->document = $document;
        $this->processing = true;

        try {
            if (empty($document->extracted_data)) {
                $extractor->process($document);
                $document->refresh();
            }

            $this->fields = $document->extracted_data ?? [];
            $this->hasData = !empty($this->fields);

            if (!$this->hasData) {
                $this->error = "No data could be extracted from the document. Please try again or upload a different file.";
            }
        } catch (\Exception $e) {
            Log::error("Document processing error: " . $e->getMessage());
            $this->error = "Error processing document: " . $e->getMessage();
        } finally {
            $this->processing = false;
        }
    }

    public function save()
    {
        $this->document->update([
            'extracted_data' => $this->fields,
        ]);

        return redirect()->route('wizard.step3', ['document' => $this->document->id]);
    }

    public function render()
    {
        return view('livewire.wizard.step2-review')
            ->layout('livewire.components.layouts.app');
    }
}
