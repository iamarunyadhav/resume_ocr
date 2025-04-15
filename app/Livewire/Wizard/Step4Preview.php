<?php

namespace App\Livewire\Wizard;

use App\Models\Document;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Step4Preview extends Component
{
    public Document $document;

    public function mount(Document $document)
    {
        $this->document = $document;
    }

    public function downloadResume(): StreamedResponse
    {
        $data = $this->document->extracted_data;

        // Optional: format for the view
        $pdf = Pdf::loadView('pdf.resume', ['data' => $data]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'resume.pdf');
    }

    public function render()
    {
        return view('livewire.wizard.step4-preview', [
            'document' => $this->document
        ])->layout('livewire.components.layouts.app');
    }
}
