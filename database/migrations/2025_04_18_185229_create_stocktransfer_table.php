<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('stocktransfer', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->enum('status', ['Création', 'Validation', 'Refus'])->default('Création');

            $table->unsignedInteger('from')->nullable();
            $table->text('refusal_reason')->nullable();
            $table->unsignedInteger('to');

            $table->timestamps();
            $table->softDeletes();
        });
    }

  
    public function down(): void
    {
        Schema::dropIfExists('stocktransfer');
    }
};