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
        Schema::create('no_tag_parts', function (Blueprint $table) {
            $table->id();
            $table->string('part', 255);
            $table->string('bin', 50);
            $table->float('count');
            $table->string('uom', 50);
            $table->tinyInteger('by_weight');
            $table->date('date_counted');
            $table->string('company', 20);
            $table->string('plant', 50);
            $table->string('lot_number', 50);
            $table->string('serial_number', 50);
            $table->string('user', 50);
            $table->float('expected_qty');
            $table->float('standard_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('no_tag_parts');
    }
};
