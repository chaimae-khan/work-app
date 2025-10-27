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
        Schema::create('daily_consumption', function (Blueprint $table) {
            $table->id();
            $table->date('consumption_date');
            $table->foreignId('vente_id')->nullable()->constrained('ventes')->onDelete('set null');
            $table->foreignId('achat_id')->nullable()->constrained('achats')->onDelete('set null');
            $table->string('type_commande')->nullable(); 
            $table->string('type_menu')->nullable();
            $table->integer('total_people')->default(0); 
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('total_tva', 10, 2)->default(0);
            $table->decimal('average_cost_per_person', 10, 2)->default(0);
            $table->json('category_costs')->nullable(); // New JSON column for category costs
            $table->unsignedInteger('eleves')->default(0);
            $table->unsignedInteger('personnel')->default(0);
            $table->unsignedInteger('invites')->default(0);
            $table->unsignedInteger('divers')->default(0);
            $table->enum('type', ['entree', 'sortie'])->default('sortie'); 
            $table->timestamps();
        });

        Schema::create('consumption_product_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumption_id')->constrained('daily_consumption')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('ligne_vente_id')->nullable()->constrained('ligne_vente')->onDelete('set null');
            $table->foreignId('ligne_achat_id')->nullable()->constrained('ligne_achat')->onDelete('set null');
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 10, 2); // Prix Unitaire (P.U.)
            $table->decimal('tva_rate', 5, 2)->default(0); // Taux de TVA (%)
            $table->decimal('tva_amount', 10, 2)->default(0); // Montant de TVA
            $table->decimal('total_price', 10, 2); // Prix Total (P.T.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumption_product_details');
        Schema::dropIfExists('daily_consumption');
    }
};