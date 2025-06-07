<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenFoodFactsService
{
    protected $baseUrl = 'https://world.openfoodfacts.org';

    public function getProductByBarcode(string $barcode)
    {
        try {
            $response = Http::timeout(30)->get("{$this->baseUrl}/api/v0/product/{$barcode}.json");
            
            if ($response->successful() && $response->json('status') === 1) {
                $product = $response->json('product');
                
                return [
                    'name' => $product['product_name'] ?? null,
                    'description' => $product['ingredients_text'] ?? null,
                    'image' => $product['image_url'] ?? null,
                    'barcode' => $barcode,
                    'success' => true
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Product not found or invalid barcode'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch product data: ' . $e->getMessage()
            ];
        }
    }
} 