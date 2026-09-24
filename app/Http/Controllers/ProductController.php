<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    //
    public function index(): AnonymousResourceCollection
{
    $products = Product::query()
        ->where('valid_until', '>=', now())
        ->latest()
        ->paginate(10);

    return ProductResource::collection($products);
}
public function store(StoreProductRequest $request): ProductResource
{
    $product = Product::create($request->validated());

    return new ProductResource($product);
}
public function show(Product $product): ProductResource
{
    return new ProductResource($product);
}

public function update( UpdateProductRequest $request,Product $product): ProductResource {
    $product->update($request->validated());

    return new ProductResource($product->fresh());
}
public function destroy(Product $product): JsonResponse
{
    $product->delete();

    return response()->json([
        'message' => 'Product deleted successfully.',
    ]);
}
}
