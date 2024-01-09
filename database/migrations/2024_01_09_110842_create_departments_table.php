<?php

use App\Models\Organization;
use Carbon\Carbon;
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
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_uz');
            $table->string('name_uz_cy');
            $table->string('name_ru');
            $table->string('name_en');
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('slug');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->unique(['slug', 'organization_id', 'parent_id']);
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('restrict');
            $table->foreign('parent_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });

        DB::table('departments')->insert([
            'name_uz'           => '',
            'name_uz_cy'        => '',
            'name_ru'           => '',
            'name_en'           => '',
            'organization_id'   => DB::table('regions')->where('type', Organization::SUBSIDIARY)->where('slug', 'andijan-mechanical-factory')->first()->id,
            'parent_id'         => null,
            'slug'              => '',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
