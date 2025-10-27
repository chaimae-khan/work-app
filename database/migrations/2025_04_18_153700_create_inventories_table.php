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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->date('date');
            $table->decimal('entree', 10, 2)->default(0);
            $table->decimal('sortie', 10, 2)->default(0);
            $table->decimal('reste', 10, 2);
            $table->decimal('prix_unitaire', 10, 2)->nullable();
            $table->foreignId('id_achat')->nullable()->references('id')->on('achats')->onDelete('set null');
            $table->foreignId('id_vente')->nullable()->references('id')->on('ventes')->onDelete('set null');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};