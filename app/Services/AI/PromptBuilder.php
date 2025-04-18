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
        return <<<'PROMPT'
        Analyze the resume content and extract information in STRICT JSON format following this schema:
        {
          "name": "string",
          "email": "string",
          "phone": "string",
          "summary": "string",
          "skills": ["string"],
          "experience": [{
            "company": "string",
            "role": "string",
            "years": "string",
            "achievements": ["string"]
          }],
          "education": [{
            "degree": "string",
            "university": "string",
            "year": "string"
          }],
          "certifications": ["string"],
          "languages": ["string"],
          "projects": ["string"],
          "awards": ["string"]
        }

        IMPORTANT RULES:
        1. Only return valid JSON
        2. Escape all special characters properly
        3. If a field is missing, use empty value ("" or [])
        4. Normalize dates to YYYY or YYYY-MM format
        5. Clean all text fields (remove extra spaces, special chars)
        PROMPT;
    }

    public static function getUnstructuredPrompt(): string
    {
        return <<<'PROMPT'
        This resume appears unstructured. Extract all possible information following these guidelines:
        1. Identify sections even if not properly labeled
        2. Extract personal details from header
        3. For experience, look for company names, job titles, and dates
        4. For education, look for degrees, institutions, and years
        5. For skills, collect both listed skills and those mentioned in descriptions
        6. Return STRICT JSON with this structure:
        {
          "name": "",
          "email": "",
          "phone": "",
          "skills": [],
          "experience": [{
            "company": "",
            "role": "",
            "years": "",
            "description": ""
          }],
          "education": [{
            "degree": "",
            "university": "",
            "year": ""
          }],
          "other_sections": {
            "section_name": "content"
          }
        }
        7. Escape all special characters
        8. If unsure about structure, put content in "other_sections"
        PROMPT;
    }

    public static function getErrorRecoveryPrompt(): string
    {
        return <<<'PROMPT'
        The previous JSON extraction failed. Please analyze this resume again and:
        1. Only return valid JSON
        2. Escape all special characters
        3. If a field contains problematic characters, simplify it
        4. Use this minimal structure:
        {
          "name": "",
          "contact": "",
          "skills": [],
          "experience": [],
          "education": [],
          "raw_text": "fallback content if needed"
        }
        PROMPT;
    }
}
