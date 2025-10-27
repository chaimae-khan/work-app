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
        Schema::create('line_transfer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('id_product')->constrained('products')->onDelete('cascade');
            $table->foreignId('id_tva')->nullable()->constrained('tvas')->onDelete('set null');
            $table->foreignId('id_unite')->nullable()->constrained('unite')->onDelete('set null');
            $table->foreignId('idcommande')->constrained('ventes')->onDelete('cascade');
         
            $table->foreignId('id_stocktransfer')->constrained('stocktransfer')->onDelete('cascade');
            
            $table->unsignedInteger('quantite')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('line_transfer');
    }
};