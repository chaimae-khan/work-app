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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code_article');
            $table->decimal('price_achat', 10, 2);
            $table->string('code_barre')->nullable();
            $table->string('emplacement')->nullable();
            $table->integer('seuil')->default(0);
            $table->date('date_expiration')->nullable(); 
            $table->foreignId('id_categorie')->references('id')->on('categories');
            $table->foreignId('id_subcategorie')->references('id')->on('sub_categories');
            $table->foreignId('id_local')->references('id')->on('locals');
            $table->foreignId('id_rayon')->references('id')->on('rayons');
            $table->foreignId('id_tva')->nullable()->references('id')->on('tvas');
            $table->foreignId('id_unite')->nullable()->references('id')->on('unite');
            $table->foreignId('id_user')->references('id')->on('users');
            $table->string('class')->nullable();

            $table->string('photo')->nullable(); 
            $table->decimal('price_vente', 10, 2)->default(1.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};