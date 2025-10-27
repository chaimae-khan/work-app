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
        Schema::create('fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->string('entreprise');
            $table->string('Telephone');
            $table->foreignId('iduser')->references('id')->on('users')->onDelete('cascade');
            $table->string('Email');
            $table->string('ICE')->nullable();
            $table->string('siege_social')->nullable();
            $table->string('RC')->nullable();
            $table->string('Patente')->nullable();
            $table->string('IF')->nullable();
            $table->string('CNSS')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fournisseurs');
    }
};