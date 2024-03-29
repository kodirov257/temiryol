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
        Schema::create('instrument_types', function (Blueprint $table) {
            $table->id();
            $table->string('name_uz');
            $table->string('name_uz_cy');
            $table->string('name_ru');
            $table->string('name_en');
            $table->text('description_uz');
            $table->text('description_uz_cy');
            $table->text('description_ru');
            $table->text('description_en');
            $table->string('photo')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });

        Schema::table('instrument_types', function (Blueprint $table) {
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrument_types');
    }
};
