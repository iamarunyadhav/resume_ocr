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
    public array $appliedSuggestions = [];
    public bool $isProcessing = false;
    public array $newItem = []; // For storing new items before adding

    public function mount(Document $document)
    {
        $this->fields = $document->extracted_data ?? [];
        $this->appliedSuggestions = array_keys($this->fields);
    }

    public function applySuggestion(string $suggestion)
    {
        $this->isProcessing = true;

        try {
            /** @var AIServiceInterface $ai */
            $ai = app(AIServiceInterface::class);

            $prompt = "Given the resume content, extract additional '$suggestion' data in JSON format.";
            $response = $ai->extractWithPrompt($this->document->path, $prompt);

            // Merge new data
            $this->fields = array_merge($this->fields, $response);
            $this->appliedSuggestions[] = $suggestion;
            $this->appliedSuggestions = array_unique($this->appliedSuggestions);

            // Save suggestions to DB
            $this->document->update([
                'extracted_data' => $this->fields,
            ]);
        } finally {
            $this->isProcessing = false;
        }
    }

    public function removeField(string $field)
    {
        if (array_key_exists($field, $this->fields)) {
            unset($this->fields[$field]);
            $this->appliedSuggestions = array_diff($this->appliedSuggestions, [$field]);

            // Update document
            $this->document->update([
                'extracted_data' => $this->fields,
            ]);
        }
    }

    // Add a new item to a simple array field (like skills)
    public function addArrayItem(string $key)
    {
        if (!empty($this->newItem[$key])) {
            if (!isset($this->fields[$key])) {
                $this->fields[$key] = [];
            }

            $this->fields[$key][] = $this->newItem[$key];
            $this->newItem[$key] = '';

            $this->document->update([
                'extracted_data' => $this->fields,
            ]);
        }
    }

    // Remove an item from an array field
    public function removeArrayItem(string $key, int $index)
    {
        if (isset($this->fields[$key][$index])) {
            unset($this->fields[$key][$index]);
            $this->fields[$key] = array_values($this->fields[$key]); // Reindex array

            if (empty($this->fields[$key])) {
                unset($this->fields[$key]);
                $this->appliedSuggestions = array_diff($this->appliedSuggestions, [$key]);
            }

            $this->document->update([
                'extracted_data' => $this->fields,
            ]);
        }
    }

    // Add a new nested item to a complex array field (like experience)
    public function addNestedItem(string $key)
    {
        if (!isset($this->fields[$key])) {
            $this->fields[$key] = [];
        }

        // Add a new empty item with default structure
        $this->fields[$key][] = $this->getDefaultStructureForField($key);

        $this->document->update([
            'extracted_data' => $this->fields,
        ]);
    }

    // Helper method to provide default structure for nested fields
    protected function getDefaultStructureForField(string $key): array
    {
        // Define default structures for different field types
        $defaults = [
            'experience' => [
                'job_title' => '',
                'company' => '',
                'dates' => '',
                'description' => []
            ],
            'education' => [
                'degree' => '',
                'institution' => '',
                'dates' => '',
                'description' => []
            ],
            // Add more default structures as needed
        ];

        return $defaults[$key] ?? ['value' => '']; // Fallback
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
