<?php

namespace App\Services;
use App\Models\Product;

class ProductSearchService
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private OpenAIService $openAIService
    ) {
    }
     public function interpretSearch(string $query): array
    {
        $categories = Product::query()
        ->whereNotNull('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category')
        ->values()
        ->all();
        $schema = [
            'type' => 'object',

            'properties' => [
                'destination' => [
                    'type' => ['string', 'null'],
                ],

               'category' => [
                    'type' => ['string', 'null'],
                'enum' => array_merge($categories, [null]),
                ],
                  'category_requested' => [
                   'type' => 'boolean',
                     ],

                'status' => [
                    'type' => ['string', 'null'],
                ],

                'min_price' => [
                    'type' => ['number', 'null'],
                ],

                'max_price' => [
                    'type' => ['number', 'null'],
                ],
            ],

            'required' => [
                'destination',
                'category',
                 'category_requested',
                'status',
                'min_price',
                'max_price',
            ],

            'additionalProperties' => false,
        ];

        $systemPrompt = <<<PROMPT
You are a search query interpreter for a travel product management system.

Your job is to convert the user's natural-language search request
into structured filters that can be used to search a database.

Rules:
- Extract only information explicitly requested or clearly implied by the user.
- If a filter is not specified, return null.
- destination should contain the requested destination.
- category must be one of the available database categories provided below, or null if no category is requested.
- category_requested must be true when the user explicitly asks for a product category or service type, even if no matching database category exists.
- category_requested must be false when the user does not specify a category.
- If a category is requested and it matches an available database category, return that database category in category.
- If a category is requested but no available database category matches it, return null for category and true for category_requested.
- If the user's wording refers to a category using different words, map it to the closest available database category.
- status can only be "active" or "inactive".
- min_price represents the lowest requested price.
- max_price represents the highest requested price.
- Do not search for products yourself.
- Do not invent products.
- Do not return any fields outside the provided schema.
PROMPT;

$categoriesText = implode(', ', $categories);

$fullPrompt = $systemPrompt
    ."\n\nAvailable database categories:\n"
    .$categoriesText
    ."\n\nUser search query:\n"
    .$query;
        return $this->openAIService->generateStructuredResponse(
            $fullPrompt,
            $schema,
            'travel_product_search'
        );
    }
    public function searchProducts(array $filters)
{
     // If the user explicitly requested a category,
    // but AI could not map it to an available database category,
    // there can be no matching products.
    if (
        ($filters['category_requested'] ?? false) === true
        && empty($filters['category'])
    ) {
        return collect();
    }
    $query = Product::query()
        ->where('valid_until', '>=', now());

    if (!empty($filters['destination'])) {
        $query->where(
            'destination',
            'like',
            '%' . $filters['destination'] . '%'
        );
    }

    if (!empty($filters['category'])) {
        $query->where(
            'category',
            'like',
            '%' . $filters['category'] . '%'
        );
    }

    if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
    }

    if ($filters['min_price'] !== null) {
        $query->where('price', '>=', $filters['min_price']);
    }

    if ($filters['max_price'] !== null) {
        $query->where('price', '<=', $filters['max_price']);
    }

    return $query
        ->latest()
        ->get();
}
public function search(string $query)
{
    $filters = $this->interpretSearch($query);

    return $this->searchProducts($filters);
}
}
