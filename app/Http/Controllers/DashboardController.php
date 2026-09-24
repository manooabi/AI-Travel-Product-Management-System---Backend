<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $now = now();

        $totalProducts = Product::count();

        $activeProducts = Product::query()
            ->where('status', 'active')
            ->where('valid_until', '>=', $now)
            ->count();

        $expiredProducts = Product::query()
            ->where('valid_until', '<', $now)
            ->count();

        return response()->json([
            'data' => [
                'total_products' => $totalProducts,
                'active_products' => $activeProducts,
                'expired_products' => $expiredProducts,
            ],
        ]);
    }
}