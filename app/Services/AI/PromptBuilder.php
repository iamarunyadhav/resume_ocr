<?php

namespace App\Services\AI;

class PromptBuilder
{
    public static function defaultPrompt(string $text): string
    {
        return "Extract structured fields from the following resume text as JSON:\n\n" . $text;
    }

    public static function withContext(string $text, string $context): string
    {
        return "Extract key-value fields for '$context' from this document text in JSON format:\n\n" . $text;
    }
}
?>
