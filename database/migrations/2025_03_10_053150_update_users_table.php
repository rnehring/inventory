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
            $table->dropColumn('name');
            $table->dropColumn('email_verified_at');
            $table->integer('user_type');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('initials')->nullable();
            $table->string('company');
            $table->string('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name');
            $table->timestamp('email_verified_at');
            $table->dropColumn('user_type');
            $table->dropColumn('first_name')->nullable();
            $table->dropColumn('last_name')->nullable();
            $table->dropColumn('initials')->nullable();
            $table->dropColumn('company');
            $table->dropColumn('location');
        });
    }
};
