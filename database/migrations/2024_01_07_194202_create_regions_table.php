<?php

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
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name_uz');
            $table->string('name_uz_cy');
            $table->string('name_ru');
            $table->string('name_en');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('type');
            $table->string('slug');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by');
            $table->timestamps();
        });

        Schema::table('regions', function (Blueprint $table) {
            $table->unique(['slug', 'parent_id']);
            $table->foreign('parent_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('restrict');
        });

        DB::table('regions')->insert([
            'name_uz'           => 'Toshkent',
            'name_uz_cy'        => 'Тошкент',
            'name_ru'           => 'Ташкент',
            'name_en'           => 'Tashkent',
            'parent_id'         => null,
            'type'              => Region::CAPITAL,
            'slug'              => 'tashkent',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('regions')->insert([
            'name_uz'           => 'Andijon',
            'name_uz_cy'        => 'Андижон',
            'name_ru'           => 'Андижан',
            'name_en'           => 'Andijan',
            'parent_id'         => null,
            'type'              => Region::REGION,
            'slug'              => 'andijan',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('regions')->insert([
            'name_uz'           => 'Andijon',
            'name_uz_cy'        => 'Андижон',
            'name_ru'           => 'Андижан',
            'name_en'           => 'Andijan',
            'parent_id'         => DB::table('regions')->where('type', Region::REGION)->where('slug', 'andijan')->first()->id,
            'type'              => Region::CITY,
            'slug'              => 'andijan-city',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('regions')->insert([
            'name_uz'           => 'Paxtaobod tumani',
            'name_uz_cy'        => 'Пахтаобод тумани',
            'name_ru'           => 'Пахтаабадский район',
            'name_en'           => 'Paxtaobod District',
            'parent_id'         => DB::table('regions')->where('type', Region::REGION)->where('slug', 'andijan')->first()->id,
            'type'              => Region::DISTRICT,
            'slug'              => 'paxtaobod',
            'created_by'        => 1,
            'updated_by'        => 1,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('regions')->insert([
            'name_uz'           => 'Paxtaobod',
            'name_uz_cy'        => 'Пахтаобод',
            'name_ru'           => 'Пахтаабад',
            'name_en'           => 'Paxtaobod',
            'parent_id'         => DB::table('regions')->where('type', Region::DISTRICT)->where('slug', 'paxtaobod')->first()->id,
            'type'              => Region::CENTER,
            'slug'              => 'paxtaobod-city',
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
        Schema::dropIfExists('regions');
    }
};
