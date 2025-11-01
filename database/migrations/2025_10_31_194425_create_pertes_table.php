<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_product')->constrained('products')->onDelete('cascade');
            $table->foreignId('id_category')->constrained('categories')->onDelete('cascade');
            $table->foreignId('id_subcategorie')->constrained('sub_categories')->onDelete('cascade');
            $table->foreignId('id_unite')->constrained('unite')->onDelete('cascade');
            $table->string('classe');
            $table->string('designation'); // Product name at time of loss
            $table->decimal('quantite', 10, 2);
            $table->string('nature'); // Nature of loss
            $table->date('date_perte'); // Date of loss
            $table->text('cause'); // Cause/reason for loss
            $table->enum('status', ['En attente', 'Validé', 'Refusé'])->default('En attente');
            $table->text('refusal_reason')->nullable();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade'); // User who declared the loss
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pertes');
    }
};