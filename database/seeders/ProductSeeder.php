<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'productName' => 'Naruto Shippuden Figurine',
            'category' => 'Figures',
            'price' => 49.99,
            'image' => 'products/naruto_figurine.jpg',
        ]);

        Product::create([
            'productName' => 'Demon Slayer Kimono',
            'category' => 'Clothing',
            'price' => 39.99,
            'image' => 'products/demon_slayer_kimono.jpg',
        ]);

        Product::create([
            'productName' => 'My Hero Academia Poster',
            'category' => 'Posters',
            'price' => 19.99,
            'image' => 'products/my_hero_poster.jpg',
        ]);

        Product::create([
            'productName' => 'One Piece Straw Hat',
            'category' => 'Accessories',
            'price' => 24.99,
            'image' => 'products/one_piece_hat.jpg',
        ]);

        Product::create([
            'productName' => 'Attack on Titan Statue',
            'category' => 'Figures',
            'price' => 89.99,
            'image' => 'products/aot_statue.jpg',
        ]);

        Product::create([
            'productName' => 'Dragon Ball Z T-Shirt',
            'category' => 'Clothing',
            'price' => 24.99,
            'image' => 'products/dbz_tshirt.jpg',
        ]);
    }
}