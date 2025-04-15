<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\OCR\OCRServiceInterface;
use App\Services\OCR\GoogleVisionOCRService;
use App\Services\AI\AIServiceInterface;
use App\Services\AI\DeepSeekService;

class ServiceBinderProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OCRServiceInterface::class, GoogleVisionOCRService::class);
        $this->app->bind(AIServiceInterface::class, DeepSeekService::class);
    }
}
?>
