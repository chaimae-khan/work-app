<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Local;
use App\Models\Rayon;
use App\Models\Tva;
use App\Models\Unite;
use App\Models\User;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Product::class;
    public function definition(): array
    {
        // Get a random category and subcategory
        $category = Category::inRandomOrder()->first() ?? Category::factory()->create();
        $subcategory = SubCategory::where('id_categorie', $category->id)->inRandomOrder()->first() ?? SubCategory::factory()->create(['id_categorie' => $category->id]);

        // Generate the product code using the method and ensure it is unique
        $code_article = Product::generateCodeArticle($category->name, $subcategory->name);

        return [
            'name' => $this->faker->word(),
            'code_article' => $code_article,
            'price_achat' => $this->faker->randomFloat(2, 10, 500),
            'price_vente' => $this->faker->randomFloat(2, 15, 600),
            'code_barre' => $this->faker->unique()->ean13(),
            'emplacement' => $this->faker->sentence(),
            'seuil' => $this->faker->numberBetween(0, 100),
            'id_categorie' => $category->id,
            'id_subcategorie' => $subcategory->id,
            'id_local' => Local::inRandomOrder()->first()->id ?? Local::factory()->create()->id,
            'id_rayon' => Rayon::inRandomOrder()->first()->id ?? Rayon::factory()->create()->id,
            'id_user' => User::inRandomOrder()->first()->id ?? User::factory()->create()->id,
            'id_tva' => Tva::inRandomOrder()->first()->id ?? Tva::factory()->create()->id,
            'id_unite' => Unite::inRandomOrder()->first()->id ?? Unite::factory()->create()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }



}