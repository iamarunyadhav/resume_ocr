<?php

namespace App\Livewire\Wizard;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Document;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Step1Upload extends Component
{
    use WithFileUploads;

    public $file;
    public $context = 'resume'; // default
    public $success = false;
    public $uploading = false;

    public function save()
    {
        $this->uploading = true;
        $this->validate([
            'file' => 'required|file|mimes:pdf,docx',
        ]);

        $filename = time().'_'.$this->file->getClientOriginalName();
        $path = $this->file->storeAs('documents', $filename, 'public');

        $document = Document::create([
            'filename' => $filename,
            'path' => $path,
            'context' => $this->context,
        ]);

        // Redirect to step 2 with document id
        return redirect()->route('wizard.step2', ['document' => $document->id]);
    }

    public function render()
    {
        return view('livewire.wizard.step1-upload')
            ->layout('livewire.components.layouts.app');
    }
}
