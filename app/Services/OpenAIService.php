<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenAIService
{
    public function generateStructuredResponse(
        string $prompt,
        array $schema,
        string $schemaName
    ): array {
        $response = Http::withToken(config('services.openai.api_key'))
            ->post('https://api.openai.com/v1/responses', [
                'model' => 'gpt-5-mini',
                'input' => $prompt,
                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => $schemaName,
                        'schema' => $schema,
                        'strict' => true,
                    ],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'OpenAI API request failed: '.$response->body()
            );
        }

        $output = $response->json('output');

        foreach ($output as $item) {
            if (($item['type'] ?? null) === 'message') {
                foreach ($item['content'] ?? [] as $content) {
                    if (($content['type'] ?? null) === 'output_text') {
                        $decoded = json_decode(
                            $content['text'],
                            true
                        );

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            throw new RuntimeException(
                                'OpenAI returned invalid JSON.'
                            );
                        }

                        return $decoded;
                    }
                }
            }
        }

        throw new RuntimeException(
            'OpenAI response did not contain structured output.'
        );
    }

    public function generateResponse(string $prompt): string
    {
        $response = Http::withToken(config('services.openai.api_key'))
            ->post('https://api.openai.com/v1/responses', [
                'model' => 'gpt-5-mini',
                'input' => $prompt,
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                'OpenAI API request failed: '.$response->body()
            );
        }

        $output = $response->json('output');

        foreach ($output as $item) {
            if (($item['type'] ?? null) === 'message') {
                foreach ($item['content'] ?? [] as $content) {
                    if (($content['type'] ?? null) === 'output_text') {
                        return $content['text'];
                    }
                }
            }
        }

        throw new RuntimeException(
            'OpenAI response did not contain output text.'
        );
    }
}