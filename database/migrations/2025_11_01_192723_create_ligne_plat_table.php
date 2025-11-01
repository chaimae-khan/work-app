<?php
// Migration for ligne_plat table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Support;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ligne_plat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_plat')->constrained('plats')->onDelete('cascade');
            $table->foreignId('idproduit')->constrained('products')->onDelete('cascade');
            $table->foreignId('id_unite')->constrained('unite')->onDelete('cascade');
            $table->decimal('qte', 10, 2);
            $table->integer('nombre_couvert');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ligne_plat');
    }
};