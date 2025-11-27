<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barrios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->double('lat');  // Latitud del centro aproximado
            $table->double('lng');  // Longitud del centro aproximado
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barrios');
    }
};