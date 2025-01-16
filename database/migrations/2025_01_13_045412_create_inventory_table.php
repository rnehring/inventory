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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('tag', 255);
            $table->string('part', 255);
            $table->string('part_description', 255);
            $table->string('bin', 50);
            $table->string('description', 255);
            $table->string('lot_number', 100);
            $table->string('serial_number', 100);
            $table->float('count');
            $table->tinyInteger('by_weight');
            $table->string('uom', 50);
            $table->string('activity_before_count', 200);
            $table->string('returned', 50);
            $table->string('user', 50);
            $table->date('date_counted');
            $table->time('time_counted');
            $table->string('note',255);
            $table->string('has_transactions', 50);
            $table->string('sheet_number', 50);
            $table->string('tag_status', 50);
            $table->string('enable_uom_worksheet', 50);
            $table->dateTime('period_end_date');
            $table->dateTime('period_start_date');
            $table->string('cycle_period', 200);
            $table->string('company', 50);
            $table->string('warehouse', 200);
            $table->string('expected_qty', 255);
            $table->string('standard_cost', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
