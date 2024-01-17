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
        Schema::create('department_instrument_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id');
            $table->unsignedInteger('department_id');
            $table->integer('quantity')->default(0);
        });

        Schema::table('department_instrument_types', function (Blueprint $table) {
            $table->unique(['type_id', 'department_id']);
            $table->foreign('type_id')->references('id')->on('instrument_types')->onDelete('restrict');
            $table->foreign('department_id')->references('id')->on('department_departments')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_instrument_types');
    }
};
