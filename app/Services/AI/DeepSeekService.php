<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use App\Services\OCR\OCRServiceInterface;
use App\Services\AI\PromptBuilder;

class DeepSeekService implements AIServiceInterface
{
    public function extractFromText(string $rawText): array
    {
        // dd("scsc",$rawText);
        $prompt = PromptBuilder::defaultPrompt($rawText);
        // dd("promt",$prompt);
        return $this->sendPrompt($prompt);
    }

    public function extractWithPrompt(string $filePath, string $customPrompt): array
    {
        return $this->sendPrompt($customPrompt);
    }

    // protected function sendPrompt(string $prompt): array
    // {

    //     // $deepSeekApiKey = env('DEEPSEEK_API_KEY');
    //     $deepSeekApiKey='sk-3d4f939b321d414abada60d9608f2e90';
    //     // dd("ff",$deepSeekApiKey);
    //     // $response = Http::withToken($deepSeekApiKey)
    //     //     ->post('https://api.deepseek.com/v1/chat/completions', [
    //     //         'model' => 'deepseek-chat',
    //     //         'messages' => [
    //     //             ['role' => 'user', 'content' => $prompt]
    //     //         ],
    //     //     ]);


    //     // Option 1: If the API expects "Bearer TOKEN"
    //     $response = Http::withToken($deepSeekApiKey)
    //     ->post('https://api.deepseek.com/v1/chat/completions', [
    //         'model' => 'deepseek-chat',
    //         'messages' => [
    //             ['role' => 'user', 'content' => $prompt]
    //         ],
    //     ]);

    //     // Option 2: If the API expects just the raw API key (no "Bearer")
    //     // $response = Http::withHeaders([
    //     // 'Authorization' => $deepSeekApiKey,
    //     // ])->post('https://api.deepseek.com/v1/chat/completions', [
    //     // 'model' => 'deepseek-chat',
    //     // 'messages' => [
    //     //     ['role' => 'user', 'content' => $prompt]
    //     // ],
    //     // ]);
    //     //  dd("sxsxsx",$deepSeekApiKey,$response);
    //     $data = $response->json();
    //     return json_decode($data['choices'][0]['message']['content'] ?? '{}', true);
    // }

    public function sendPrompt(string $resumeText): array
    {
        $deepSeekApiKey = env('DEEPSEEK_API_KEY');

        $response = Http::withToken($deepSeekApiKey)
            ->timeout(30)
            ->post('https://api.deepseek.com/v1/chat/completions', [
                'model' => 'deepseek-chat',
                'response_format' => ['type' => 'json_object'], // Force JSON
                'messages' => [
                    ['role' => 'system', 'content' => PromptBuilder::get()],
                    ['role' => 'user', 'content' => $resumeText]
                ],
            ]);

        if (!$response->successful()) {
            throw new \Exception("API Error: " . $response->body());
        }

        $content = $response->json()['choices'][0]['message']['content'];

        // Validate JSON
        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Invalid JSON: " . $content);
        }

        return $data;
    }
}
?>
