<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use App\Services\OCR\OCRServiceInterface;
use App\Services\AI\PromptBuilder;
use Exception;
use Illuminate\Support\Str;

class DeepSeekService implements AIServiceInterface
{
    public function extractFromText(string $rawText): array
    {
        // Clean and normalize the text first
        $cleanText = $this->sanitizeText($rawText);

        try {
            $structuredResponse = $this->tryStructuredExtraction($cleanText);

            if ($this->isIncompleteResponse($structuredResponse)) {
                $unstructuredResponse = $this->tryUnstructuredExtraction($cleanText);
                return $this->mergeResponses($structuredResponse, $unstructuredResponse);
            }

            return $structuredResponse;
        } catch (Exception $e) {
            // Fallback to basic extraction if structured fails
            return $this->basicTextExtraction($cleanText);
        }
    }

    public function extractWithPrompt(string $filePath, string $customPrompt): array
    {
        $ocrService = app(OCRServiceInterface::class);
        $rawText = $ocrService->extractText($filePath);
        $cleanText = $this->sanitizeText($rawText);

        return $this->sendPrompt($customPrompt, $cleanText);
    }

    protected function tryStructuredExtraction(string $text): array
    {
        return $this->sendPrompt(PromptBuilder::get(), $text);
    }

    protected function tryUnstructuredExtraction(string $text): array
    {
        return $this->sendPrompt(PromptBuilder::getUnstructuredPrompt(), $text);
    }

    protected function sendPrompt(string $prompt, string $content): array
    {
        $maxRetries = 3;
        $retryDelay = 1000; // milliseconds
        $timeout = 120; // seconds
        $connectTimeout = 30; // seconds

        $attempt = 0;
        $lastError = null;

        while ($attempt < $maxRetries) {
            try {
                $attempt++;

                $response = Http::withToken(env('DEEPSEEK_API_KEY'))
                    ->timeout($timeout)
                    ->connectTimeout($connectTimeout)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->post('https://api.deepseek.com/v1/chat/completions', [
                        'model' => 'deepseek-chat',
                        'response_format' => ['type' => 'json_object'],
                        'temperature' => 0.7, // Control randomness
                        'max_tokens' => 4000, // Limit response size
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $this->sanitizeText($prompt)
                            ],
                            [
                                'role' => 'user',
                                'content' => $this->sanitizeText($content)
                            ]
                        ],
                    ]);

                if (!$response->successful()) {
                    throw new Exception(
                        "API request failed [Attempt {$attempt}/{$maxRetries}]: " .
                        "Status {$response->status()}, Body: " .
                        substr($response->body(), 0, 200)
                    );
                }

                $responseContent = $response->json()['choices'][0]['message']['content'];
                $processedContent = $this->processApiResponse($responseContent);

                return $processedContent;

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                $lastError = $e;
                if ($attempt < $maxRetries) {
                    usleep($retryDelay * 1000); // Convert to microseconds
                    $retryDelay *= 2; // Exponential backoff
                    continue;
                }
            } catch (\Exception $e) {
                $lastError = $e;
                break;
            }
        }

        throw new Exception(
            "Failed after {$maxRetries} attempts. Last error: " .
            $lastError?->getMessage() ?? 'Unknown error'
        );
    }

    protected function processApiResponse(string $responseContent): array
    {
        // Attempt to repair JSON if needed
        $jsonResponse = $this->attemptJsonRepair($responseContent);

        // Validate JSON structure
        $data = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);

        // Clean and normalize the data
        return $this->cleanAndNormalizeData($data);
    }

    protected function attemptJsonRepair(string $json): string
    {
        // First try simple fixes
        $repaired = preg_replace('/,\s*([}\]])/', '$1', $json); // Remove trailing commas
        $repaired = preg_replace('/([{,])(\s*)([}\]])/', '$1null$3', $repaired); // Fix empty elements

        if (json_validate($repaired)) {
            return $repaired;
        }

        // If still invalid, try more aggressive repair
        $repaired = mb_convert_encoding($repaired, 'UTF-8', 'UTF-8');
        $repaired = preg_replace('/[^\x20-\x7E\xA0-\xFF]/u', ' ', $repaired);

        // Final validation
        if (!json_validate($repaired)) {
            throw new Exception("Unable to repair malformed JSON response");
        }

        return $repaired;
    }

    protected function cleanAndNormalizeData(array $data): array
    {
        $normalized = [];

        foreach ($data as $key => $value) {
            $cleanKey = $this->normalizeKey($key);

            if (is_array($value)) {
                $normalized[$cleanKey] = $this->cleanAndNormalizeData($value);
            } elseif (is_string($value)) {
                $normalized[$cleanKey] = $this->normalizeValue($value);
            } else {
                $normalized[$cleanKey] = $value;
            }
        }

        return $normalized;
    }

    protected function normalizeKey(string $key): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9_]/', '_', $key));
    }

    protected function normalizeValue(string $value): string
    {
        // Convert to UTF-8 and remove invalid sequences
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');

        // Normalize whitespace and trim
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

        // Remove control characters except basic whitespace
        return preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
    }

    protected function sanitizeText(string $text): string
    {
        // Remove invalid UTF-8 characters
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');

        // Remove special characters that might break JSON
        $text = preg_replace('/[^\x20-\x7E\xA0-\xFF]/u', ' ', $text);

        // Normalize line endings and trim
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        return trim($text);
    }

    protected function fixJsonEncoding(string $json): string
    {
        // Fix common JSON encoding issues
        $json = preg_replace('/,\s*([}\]])/', '$1', $json); // Remove trailing commas
        $json = preg_replace('/([{\[,])\s*([}\]])/', '$1null$2', $json); // Fix empty values
        return $json;
    }

    protected function cleanArray(array $data): array
    {
        array_walk_recursive($data, function (&$value) {
            if (is_string($value)) {
                $value = $this->sanitizeText($value);
            }
        });
        return $data;
    }

    protected function isIncompleteResponse(array $data): bool
    {
        $essentialFields = ['name', 'experience', 'education', 'skills'];
        $missingCount = 0;

        foreach ($essentialFields as $field) {
            if (empty($data[$field])) {
                $missingCount++;
            }
        }

        return $missingCount > 1; // More lenient threshold
    }

    protected function mergeResponses(array $primary, array $secondary): array
    {
        foreach ($secondary as $key => $value) {
            if (empty($primary[$key]) || (is_array($primary[$key]) && empty($primary[$key]))) {
                $primary[$key] = $value;
            } elseif (is_array($primary[$key]) && is_array($value)) {
                $primary[$key] = array_merge($primary[$key], $value);
            }
        }
        return $primary;
    }

    protected function basicTextExtraction(string $text): array
    {
        // Fallback method when structured extraction fails
        return [
            'raw_text' => $text,
            'name' => $this->extractName($text),
            'email' => $this->extractEmail($text),
            'phone' => $this->extractPhone($text),
            'skills' => $this->extractSkills($text),
            'experience' => $this->extractExperience($text),
            'education' => $this->extractEducation($text)
        ];
    }

    // Basic extraction helpers
    protected function extractName(string $text): string { /* ... */ }
    protected function extractEmail(string $text): string { /* ... */ }
    protected function extractPhone(string $text): string { /* ... */ }
    protected function extractSkills(string $text): array { /* ... */ }
    protected function extractExperience(string $text): array { /* ... */ }
    protected function extractEducation(string $text): array { /* ... */ }
}
