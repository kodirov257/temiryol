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
        Schema::create('instrument_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrower_id');
            $table->unsignedBigInteger('instrument_id');
            $table->unsignedInteger('department_id');
            $table->unsignedInteger('instrument_type_id');
            $table->string('serial');
            $table->tinyInteger('type');
            $table->tinyInteger('status');
            $table->tinyInteger('instrument_status')->nullable();
            $table->timestamp('deadline');
            $table->string('unique_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });

        Schema::table('instrument_operations', function (Blueprint $table) {
            $table->foreign('borrower_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('instrument_id')->references('id')->on('instrument_instruments')->onDelete('restrict');
            $table->foreign('department_id')->references('id')->on('department_departments')->onDelete('restrict');
            $table->foreign('instrument_type_id')->references('id')->on('instrument_types')->onDelete('restrict');
            $table->foreign('parent_id')->references('id')->on('instrument_operations')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_operations');
    }
};
