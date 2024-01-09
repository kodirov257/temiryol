<?php

use App\Models\Organization;
use App\Models\Region;
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
        Schema::create('organizations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_uz');
            $table->string('name_uz_cy');
            $table->string('name_ru');
            $table->string('name_en');
            $table->unsignedInteger('region_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('type')->nullable();
            $table->string('slug');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->unique(['slug', 'region_id', 'parent_id']);
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('restrict');
            $table->foreign('parent_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });

        DB::table('organizations')->insert([
            'name_uz'           => 'O’zbekiston temir yo’llari',
            'name_uz_cy'        => 'Ўзбекистон темир йўллари',
            'name_ru'           => 'Узбекистанские железные дороги',
            'name_en'           => 'Uzbek Railways',
            'region_id'         => DB::table('regions')->where('type', Region::CAPITAL)->where('slug', 'tashkent')->first()->id,
            'parent_id'         => null,
            'type'              => Organization::PUBLIC_COMPANY,
            'slug'              => 'uzbek-railways',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('organizations')->insert([
            'name_uz'           => 'Andijon mexanika zavodi',
            'name_uz_cy'        => 'Андижон механика заводи',
            'name_ru'           => 'Андижанский механический завод',
            'name_en'           => 'Andijan mechanical factory',
            'region_id'         => DB::table('regions')->where('type', Region::CITY)->where('slug', 'andijan-city')->first()->id,
            'parent_id'         => DB::table('organizations')->where('type', Organization::PUBLIC_COMPANY)->where('slug', 'uzbek-railways')->first()->id,
            'type'              => Organization::SUBSIDIARY,
            'slug'              => 'andijan-mechanical-factory',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('organizations')->insert([
            'name_uz'           => 'Andijon mexanika zavodi Paxtaobod filiali',
            'name_uz_cy'        => 'Андижон механика заводи Пахтаобод филиали',
            'name_ru'           => 'Андижанский механический завод Пахтаабадский филиал',
            'name_en'           => 'Andijan mechanical factory Paxtaobod branch',
            'region_id'         => DB::table('regions')->where('type', Region::CENTER)->where('slug', 'paxtaobod-city')->first()->id,
            'parent_id'         => DB::table('organizations')->where('type', Organization::SUBSIDIARY)->where('slug', 'andijan-mechanical-factory')->first()->id,
            'type'              => Organization::BRANCH,
            'slug'              => 'andijan-mechanical-factory-paxtaobod-branch',
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
        Schema::dropIfExists('organizations');
    }
};
