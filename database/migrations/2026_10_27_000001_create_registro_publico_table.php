<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_publico', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->boolean('completado')->default(false);
            $table->timestamps();
        });

        DB::table('registro_publico')->insert([
            'id' => 1,
            'completado' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_publico');
    }
};
