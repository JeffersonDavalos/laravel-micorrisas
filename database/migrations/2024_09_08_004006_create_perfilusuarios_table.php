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
        Schema::create('tbm_perfil', function (Blueprint $table) {
            $table->id('id_perfil');
            $table->string('descripcion', 255);
            $table->char('estado', 1)->default('A'); 
            $table->ipAddress('ip')->default('127.0.0.1'); 
            $table->dateTime('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbm_perfilusuario');
    }
};
