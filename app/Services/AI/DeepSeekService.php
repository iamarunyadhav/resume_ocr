<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use App\Services\OCR\OCRServiceInterface;
use App\Services\AI\PromptBuilder;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Client\ConnectionException;

class DeepSeekService implements AIServiceInterface
{
    public function extractFromText(string $rawText): array
    {
        // Clean and normalize the text first
        $cleanText = $this->sanitizeText($rawText);

        try {
            $structuredResponse = $this->tryStructuredExtraction($cleanText);

            // Ensure we have at least a name field
            if (!isset($structuredResponse['name'])) {
                $structuredResponse['name'] = $this->safeExtractName($cleanText);
            }

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
                        'temperature' => 0.7,
                        'max_tokens' => 4000,
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

            } catch (ConnectionException $e) {
                $lastError = $e;
                if ($attempt < $maxRetries) {
                    usleep($retryDelay * 1000);
                    $retryDelay *= 2;
                    continue;
                }
            } catch (\Exception $e) {
                $lastError = $e;
                break;
            }
        }

        throw new Exception(
            "Failed after {$maxRetries} attempts. Last error: " .
            ($lastError ? $lastError->getMessage() : 'Unknown error')
        );
    }

    protected function processApiResponse(string $responseContent): array
    {
        $jsonResponse = $this->attemptJsonRepair($responseContent);

        try {
            $data = json_decode($jsonResponse, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new Exception("Failed to decode JSON: " . $e->getMessage());
        }

        return $this->cleanAndNormalizeData($data);
    }

    protected function attemptJsonRepair(string $json): string
    {
        $repaired = preg_replace('/,\s*([}\]])/', '$1', $json);
        $repaired = preg_replace('/([{,])(\s*)([}\]])/', '$1null$3', $repaired);

        if (json_validate($repaired)) {
            return $repaired;
        }

        $repaired = mb_convert_encoding($repaired, 'UTF-8', 'UTF-8');
        $repaired = preg_replace('/[^\x20-\x7E\xA0-\xFF\p{L}\p{N}\p{P}\p{S}]/u', ' ', $repaired);

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
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);
        return preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
    }

    protected function sanitizeText(string $text): string
    {
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = preg_replace('/[^\x20-\x7E\xA0-\xFF]/u', ' ', $text);
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        return trim($text);
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

        return $missingCount > 1;
    }

    protected function mergeResponses(array $primary, array $secondary): array
    {
        foreach ($secondary as $key => $value) {
            if (empty($primary[$key]) || $this->isMoreComplete($value, $primary[$key])) {
                $primary[$key] = $value;
            }
        }
        return $primary;
    }

    protected function isMoreComplete($new, $existing): bool
    {
        if (is_array($new) && is_array($existing)) {
            return count($new) > count($existing);
        }
        return strlen((string)$new) > strlen((string)$existing);
    }

    protected function basicTextExtraction(string $text): array
    {
        return [
            'raw_text' => $text,
            'name' => $this->safeExtractName($text),
            'email' => $this->safeExtractEmail($text),
            'phone' => $this->safeExtractPhone($text),
            'skills' => $this->safeExtractSkills($text),
            'experience' => $this->safeExtractExperience($text),
            'education' => $this->safeExtractEducation($text),
            'metadata' => [
                'status' => 'fallback',
                'warning' => 'Used basic text extraction'
            ]
        ];
    }

    protected function safeExtractName(string $text): string
    {
        try {
            // Try to find a line that looks like a name
            $lines = explode("\n", $text);
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (preg_match('/^[A-Z][a-z]+(?:\s+[A-Z][a-z]+)+$/', $trimmed)) {
                    return $trimmed;
                }
            }

            // Fallback to email extraction
            if (preg_match('/([a-zA-Z0-9._%+-]+)@/', $text, $matches)) {
                $username = str_replace(['.', '_'], ' ', $matches[1]);
                return ucwords($username);
            }

            return 'Unknown Candidate';
        } catch (\Exception $e) {
            return 'Unknown Candidate';
        }
    }

    protected function safeExtractEmail(string $text): string
    {
        if (preg_match('/\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\b/i', $text, $matches)) {
            return strtolower($matches[0]);
        }
        return '';
    }

    protected function safeExtractPhone(string $text): string
    {
        if (preg_match('/\+?[\d\s\-\(\)]{7,}/', $text, $matches)) {
            return preg_replace('/[^\d\+]/', '', $matches[0]);
        }
        return '';
    }

    protected function safeExtractSkills(string $text): array
    {
        $commonSkills = ['PHP', 'JavaScript', 'Python', 'Java', 'SQL', 'HTML', 'CSS',
                        'Laravel', 'React', 'Angular', 'Vue', 'Node.js', 'Git'];

        $foundSkills = [];
        foreach ($commonSkills as $skill) {
            if (stripos($text, $skill) !== false) {
                $foundSkills[] = $skill;
            }
        }

        return array_unique($foundSkills);
    }

    protected function safeExtractExperience(string $text): array
    {
        $experience = [];
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            if (preg_match('/(.*?)\s*\((.*?)\)/', $line, $matches)) {
                $experience[] = [
                    'role' => trim($matches[1]),
                    'company' => '',
                    'period' => trim($matches[2]),
                    'description' => ''
                ];
            }
        }

        return $experience;
    }

    protected function safeExtractEducation(string $text): array
    {
        $education = [];
        $lines = explode("\n", $text);

        foreach ($lines as $line) {
            if (preg_match('/(University|College|Institute|School)/i', $line)) {
                $education[] = [
                    'degree' => '',
                    'institution' => trim($line),
                    'year' => ''
                ];
            }
        }

        return $education;
    }
}
