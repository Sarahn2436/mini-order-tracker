<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Fashion', 'Technology', 'Cards', 'Misc'];

        foreach ($categories as $category) {
            $this->command->info("Fetching 28 products for category: {$category}...");

            $response = Http::withoutVerifying()->get('https://fakerapi.it/api/v1/products', [
                '_quantity' => 28,
                '_locale'   => 'en_US'
            ]);

            if ($response->successful()) {
                $apiData = $response->json();
                $products = $apiData['data'] ?? [];

                if (empty($products)) {
                    $this->command->error("The API data envelope key was empty for category: {$category}");
                    continue;
                }

                foreach ($products as $apiProduct) {
                    Product::create([
                        'name'        => $apiProduct['name'],
                        'slug'        => Str::slug($apiProduct['name']) . '-' . rand(1, 10000),
                        'description' => $apiProduct['description'],
                        'price'       => $apiProduct['net_price'] ?? 19.99,
                        'image'       => $apiProduct['image'] ?? 'https://placeholder.com',
                        'category'    => $category,
                    ]);
                }

                $this->command->info("Successfully seeded 28 products into '{$category}'!");
            } else {
                $this->command->error("Failed to communicate with API for '{$category}'. Status: " . $response->status());
            }
        }

        $this->command->info('All categories successfully seeded with unique products!');
    }
}