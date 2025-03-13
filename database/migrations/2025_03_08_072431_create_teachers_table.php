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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignId('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->enum('status', ['active', 'inactive','blocked'])->default('active');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('last_login')->nullable();
            $table->boolean('terms_and_conditions')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->json('academic_certificate');
            $table->string('age');
            $table->json('experience');
            $table->string('available_times')->nullable();
            $table->json('about')->nullable();
            $table->enum('contact_method', ['phone', 'meeting'])->default('meeting');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
