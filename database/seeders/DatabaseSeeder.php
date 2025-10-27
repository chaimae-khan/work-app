<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Local;
use App\Models\Rayon;
use Faker\Factory as Faker;
use App\Models\Tva;
use App\Models\Unite;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed the base permission and role system
        $this->call([
            PermissionSeeder::class,
            RoleSeeder::class,
            DefaultUserSeeder::class,
        ]);

        // Additional users
        User::factory(5)->create();

        // Categories and SubCategories
        // $categories = Category::factory(5)->create();
        // $categories->each(function ($category) {
        //     SubCategory::factory(3)->create(['id_categorie' => $category->id]);
        // });

        // Create Locals, Rayons, Tvas, and Unites
        // $locals = Local::factory(5)->create();
        // $rayons = Rayon::factory(5)->create();
        // $tvas = Tva::factory(3)->create();
        // $unites = Unite::factory(3)->create();

        // Create Products
        // $products = Product::factory(20)->create();

        // Create Stock for each product
        // $products->each(function ($product) use ($tvas, $unites) {
        //     $seuil = rand(1, 100);
        //     $quantite = rand($seuil + 1, 300);

        //     Stock::factory()->create([
        //         'id_product' => $product->id,
        //         'id_tva' => $tvas->random()->id,
        //         'id_unite' => $unites->random()->id,
        //         'quantite' => $quantite,
        //         /* 'seuil' => $seuil, */
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // });

        $this->command->info('✅ Database seeding completed successfully!');
    }

}