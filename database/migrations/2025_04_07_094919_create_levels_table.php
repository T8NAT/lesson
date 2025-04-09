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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->unsignedInteger('level_number');
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('points_reward')->default(10);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['game_id', 'level_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
