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
        $response = Http::withoutVerifying()->get('https://fakerapi.it/api/v1/products', [
            '_quantity' => 50,
            '_locale'   => 'en_US'
        ]);

        if ($response->successful()) {
            $apiData = $response->json();
            
            $products = $apiData['data'] ?? []; 

            if (empty($products)) {
                $this->command->error('The API data envelope key was empty.');
                return;
            }

            foreach ($products as $apiProduct) {
                Product::create([
                    'name'        => $apiProduct['name'],
                    'slug'        => Str::slug($apiProduct['name']) . '-' . rand(1, 1000),
                    'description' => $apiProduct['description'],
                    'price'       => $apiProduct['net_price'] ?? 19.99,
                    'image'       => $apiProduct['image'] ?? 'https://placeholder.com',
                ]);
            }
            
            $this->command->info('Seeded ' . count($products) . ' random products successfully using the v1 API framework!');
        } else {
            $this->command->error('Failed to communicate with the dynamic API framework. Status Code: ' . $response->status());
        }
    }


}