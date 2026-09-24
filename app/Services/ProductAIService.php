<?php

namespace App\Services;

class ProductAIService
{
    public function __construct(
        private OpenAIService $openAIService
    ) {
    }

    public function generateProduct(string $prompt): array
    {
        $schema = [
            'type' => 'object',
            'properties' => [
                'product_name' => [
                    'type' => 'string',
                ],

                'destination' => [
                    'type' => 'string',
                ],

                'category' => [
                    'type' => 'string',
                ],

                'description' => [
                    'type' => 'string',
                ],

                'highlights' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],

                'inclusions' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],

                'tags' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'string',
                    ],
                ],
            ],

            'required' => [
                'product_name',
                'destination',
                'category',
                'description',
                'highlights',
                'inclusions',
                'tags',
            ],

            'additionalProperties' => false,
        ];

        $systemPrompt = <<<PROMPT
You are an AI assistant for a travel product management system.

Generate professional travel product information based on the user's request.

Rules:
- Create realistic and useful travel product content.
- Keep the description clear and suitable for a travel booking platform.
- Highlights should contain important selling points.
- Inclusions should contain services or items included in the product.
- Tags should be short and useful for searching and categorization.
- Do not generate price.
- Do not generate inventory count.
- Do not generate validity dates.
- Do not generate status.
- Return only information that belongs to the requested schema.
PROMPT;

        $fullPrompt = $systemPrompt."\n\nUser request:\n".$prompt;

        return $this->openAIService->generateStructuredResponse(
            $fullPrompt,
            $schema,
            'travel_product'
        );
    }
}