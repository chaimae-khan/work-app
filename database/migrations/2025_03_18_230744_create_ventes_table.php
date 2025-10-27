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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->decimal('total', 10, 2);
            $table->enum('status', ['Création', 'Validation', 'Refus', 'Livraison', 'Réception', 'Visé'])->default('Création');
            $table->enum('type_commande', ['Alimentaire', 'Non Alimentaire', 'Fournitures et matériels'])->default('Alimentaire'); 
            $table->enum('type_menu', ['Menu eleves', 'Menu specials', 'Menu d\'\'application'])->nullable(); 
            $table->foreignId('id_client')->nullable()->references('id')->on('clients')->onDelete('cascade');
            $table->foreignId('id_formateur')->references('id')->on('users')->onDelete('cascade'); 
            $table->boolean('is_transfer')->default(false);
            $table->unsignedInteger('eleves')->default(0);
            $table->unsignedInteger('personnel')->default(0);
            $table->unsignedInteger('invites')->default(0);
            $table->unsignedInteger('divers')->default(0);
            
            $table->string('entree')->nullable();
            $table->string('plat_principal')->nullable();
            $table->string('accompagnement')->nullable();
            $table->string('dessert')->nullable();
            $table->date('date_usage')->nullable();
            
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
};