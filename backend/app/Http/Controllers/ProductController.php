<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class ProductController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // This is just an example endpoint
    public function index(): JsonResponse
    {
        // sample data
        $productOne = (object) [
            'id' => 1,
            'name' => 'Product 1',
            'price' => 100,
            'description' => 'This is product 1',
        ];

        $productTwo = (object) [
            'id' => 2,
            'name' => 'Product 2',
            'price' => 200,
            'description' => 'This is product 2',
        ];

        return response()->json([
            'success' => true,
            'message' => 'Product List',
            'products' => collect([$productOne, $productTwo])
        ], 200);
    }
}
