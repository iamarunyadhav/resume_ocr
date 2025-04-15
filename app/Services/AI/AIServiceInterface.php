<?php

namespace App\Services\AI;

interface AIServiceInterface
{
    public function extractFromText(string $rawText): array;
    public function extractWithPrompt(string $filePath, string $customPrompt): array;
}

?>
