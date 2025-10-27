<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('tmpstocktransfer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_product')->constrained('products')->onDelete('cascade');
            $table->unsignedInteger('quantite_stock')->default(0);
            $table->unsignedInteger('quantite_transfer')->default(0);
            $table->foreignId('from')->constrained('users')->onDelete('cascade');
            $table->foreignId('to')->constrained('users')->onDelete('cascade');
            $table->foreignId('iduser')->constrained('users')->onDelete('cascade');
            $table->foreignId('idcommande')->constrained('ventes')->onDelete('cascade');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tmpstocktransfer');
    }
};
