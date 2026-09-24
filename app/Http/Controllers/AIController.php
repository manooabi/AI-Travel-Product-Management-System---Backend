<?php

namespace App\Http\Controllers;

use App\Services\ProductAIService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\ProductSearchService;
use App\Http\Resources\ProductResource;
use RuntimeException;

class AIController extends Controller
{
    //
     public function __construct(
        private ProductAIService $productAIService,
        private ProductSearchService $productSearchService
    ) {
    }

    public function generateProduct(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prompt' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

         try {
        $product = $this->productAIService->generateProduct(
            $validated['prompt']
        );

        return response()->json([
            'message' => 'Product generated successfully.',
            'data' => $product,
        ]);
    } catch (RuntimeException $exception) {
        report($exception);

        return response()->json([
            'message' => 'Unable to generate product content at the moment.',
        ], 503);
    }
    }
    public function search(Request $request): JsonResponse
{
    $validated = $request->validate([
        'query' => ['required', 'string', 'min:3', 'max:500'],
    ]);

    try {
        $products = $this->productSearchService->search(
            $validated['query']
        );

        return response()->json([
            'message' => 'Search completed successfully.',
            'data' => ProductResource::collection($products),
        ]);
    } catch (RuntimeException $exception) {
        report($exception);

        return response()->json([
            'message' => 'Unable to process the search at the moment.',
        ], 503);
    }
}
}
