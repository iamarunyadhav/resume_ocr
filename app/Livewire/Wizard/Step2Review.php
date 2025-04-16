<?php

namespace App\Livewire\Wizard;

use Livewire\Component;
use App\Models\Document;
use App\Services\DocumentExtractor;

class Step2Review extends Component
{
    public Document $document;
    public array $fields = [];
    public $processing = false;

    public function mount(Document $document, DocumentExtractor $extractor)
    {
        $this->processing = true;
        try {
            $this->document = $document;
            if (empty($document->extracted_data)) {
                $extractor->process($document);
                $document->refresh();
            }
            $this->fields = $document->extracted_data ?? [];
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
