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
        Schema::create('department_departments', function (Blueprint $table) {
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

        Schema::table('department_departments', function (Blueprint $table) {
            $table->unique(['slug', 'organization_id', 'parent_id']);
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('restrict');
            $table->foreign('parent_id')->references('id')->on('department_departments')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });

        $organizationId = DB::table('organizations')->where('type', Organization::SUBSIDIARY)->where('slug', 'andijan-mechanical-factory')->first()->id;

        DB::table('department_departments')->insert([
            'name_uz'           => 'Asosiy omborxona',
            'name_uz_cy'        => 'Асосий омборхона',
            'name_ru'           => 'Центральный склад',
            'name_en'           => 'Central warehouse',
            'organization_id'   => $organizationId,
            'parent_id'         => null,
            'slug'              => 'central-warehouse',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('department_departments')->insert([
            'name_uz'           => 'BIX',
            'name_uz_cy'        => 'БИХ',
            'name_ru'           => 'БИХ',
            'name_en'           => 'BIH',
            'organization_id'   => $organizationId,
            'parent_id'         => DB::table('department_departments')->where('slug', 'central-warehouse')->first()->id,
            'slug'              => 'bih',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        $bihId = DB::table('department_departments')->where('slug', 'bih')->first()->id;

        DB::table('department_departments')->insert([
            'name_uz'           => 'Omborxona 1',
            'name_uz_cy'        => 'Омборхона 1',
            'name_ru'           => 'Склад 1',
            'name_en'           => 'Warehouse 1',
            'organization_id'   => $organizationId,
            'parent_id'         => $bihId,
            'slug'              => 'warehouse-1',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('department_departments')->insert([
            'name_uz'           => 'Omborxona 2',
            'name_uz_cy'        => 'Омборхона 2',
            'name_ru'           => 'Склад 2',
            'name_en'           => 'Warehouse 2',
            'organization_id'   => $organizationId,
            'parent_id'         => $bihId,
            'slug'              => 'warehouse-2',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('department_departments')->insert([
            'name_uz'           => 'Omborxona 3',
            'name_uz_cy'        => 'Омборхона 3',
            'name_ru'           => 'Склад 3',
            'name_en'           => 'Warehouse 3',
            'organization_id'   => $organizationId,
            'parent_id'         => $bihId,
            'slug'              => 'warehouse-3',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('department_departments')->insert([
            'name_uz'           => 'Omborxona 4',
            'name_uz_cy'        => 'Омборхона 4',
            'name_ru'           => 'Склад 4',
            'name_en'           => 'Warehouse 4',
            'organization_id'   => $organizationId,
            'parent_id'         => $bihId,
            'slug'              => 'warehouse-4',
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
        Schema::dropIfExists('department_departments');
    }
};
