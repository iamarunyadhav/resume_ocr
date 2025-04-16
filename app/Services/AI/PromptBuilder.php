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


    public static function get(): string
    {
        return <<<PROMPT
        Extract resume fields as STRICT JSON. Follow this schema:
        {
          "name": "",
          "email": "",
          "phone": "",
          "summary": "",
          "skills": [],
          "experience": [{
            "company": "",
            "role": "",
            "years": "",
            "achievements": []
          }],
          "education": [{
            "degree": "",
            "university": "",
            "year": ""
          }],
          "certifications": [],
          "languages": []
        }
        PROMPT;
    }
}
?>
