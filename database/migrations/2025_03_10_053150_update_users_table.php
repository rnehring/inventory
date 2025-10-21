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
        Schema::table('users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_type');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('initials')->nullable();
            $table->string('company');
            $table->string('location');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_type');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('initials')->nullable();
            $table->string('company');
            $table->string('location');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
};
