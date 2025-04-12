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
        Schema::create('game_level', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->foreignId('level_id');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('cascade');
            $table->unique(['game_id', 'level_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_level');
    }
};
