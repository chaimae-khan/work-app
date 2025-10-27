<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventory_yearly_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('year');
            $table->decimal('total_entrees', 10, 2)->default(0);
            $table->decimal('total_sorties', 10, 2)->default(0);
            $table->decimal('end_stock', 10, 2);
            $table->decimal('average_price', 10, 2)->nullable(); // Add this line
            $table->timestamps();
            
            // Ensure each product has only one summary per year
            $table->unique(['product_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_yearly_summaries');
    }
};