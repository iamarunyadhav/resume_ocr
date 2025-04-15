<?php

namespace App\Livewire\Wizard;

use Livewire\Component;
use App\Models\Document;
use App\Services\AI\AIServiceInterface;

class Step3Suggestions extends Component
{
    public Document $document;
    public array $fields = [];
    public array $suggestions = [
        'certifications', 'languages', 'projects', 'awards'
    ];

    public function mount(Document $document)
    {
        $this->fields = $document->extracted_data ?? [];
    }

    public function applySuggestion(string $suggestion)
    {
        /** @var AIServiceInterface $ai */
        $ai = app(AIServiceInterface::class);

        // Build prompt using context + existing fields + suggestion
        $prompt = "Given the resume content, extract additional '$suggestion' data in JSON format.";

        $response = $ai->extractWithPrompt($this->document->path, $prompt);

        // Merge new data
        $this->fields = array_merge($this->fields, $response);

        // Save suggestions to DB
        $this->document->update([
            'extracted_data' => $this->fields,
        ]);
    }

    public function save()
    {
        $this->document->update([
            'extracted_data' => $this->fields,
        ]);
        return redirect()->route('wizard.step4', ['document' => $this->document->id]);
    }

    public function render()
    {
        return view('livewire.wizard.step3-suggestions')
            ->layout('livewire.components.layouts.app');
    }
}
