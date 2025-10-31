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
        Schema::create('pertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_category')->nullable()->constrained('categories')->onDelete('cascade');
            $table->foreignId('id_sub_categories')->nullable()->constrained('sub_categories')->onDelete('cascade');
            $table->string('class')->nullable();
            $table->foreignId('id_product')->nullable()->constrained('products')->onDelete('cascade');
            $table->foreignId('id_unite')->nullable()->constrained('unite')->onDelete('cascade');
            $table->string('nature')->nullable();
            $table->integer('qte')->nullable();
            $table->string('date')->nullable();
            $table->string('cause')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertes');
    }
};
