<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', static function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 120);
            $table->string('role');
            $table->integer('status');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert([
            'name'              => 'admin',
            'email'             => 'admin@gmail.com',
            'password'          => bcrypt('1q2w3e4r5t6y'),
            'email_verified_at' => Carbon::now()->addSeconds(300),
            'role'              => User::ROLE_ADMIN,
            'status'            => User::STATUS_ACTIVE,
            'remember_token'    => Str::random(10),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name'              => 'accountant',
            'email'             => 'accountant@gmail.com',
            'password'          => bcrypt('1q2w3e4r5t6y'),
            'email_verified_at' => Carbon::now()->addSeconds(300),
            'role'              => User::ROLE_ACCOUNTANT,
            'status'            => User::STATUS_ACTIVE,
            'remember_token'    => Str::random(10),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name'              => 'worker',
            'email'             => 'worker@gmail.com',
            'password'          => bcrypt('1q2w3e4r5t6y'),
            'email_verified_at' => Carbon::now()->addSeconds(300),
            'role'              => User::ROLE_WORKER,
            'status'            => User::STATUS_ACTIVE,
            'remember_token'    => Str::random(10),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);

        DB::table('users')->insert([
            'name'              => 'user',
            'email'             => 'user@gmail.com',
            'password'          => bcrypt('1q2w3e4r5t6y'),
            'email_verified_at' => Carbon::now()->addSeconds(300),
            'role'              => User::ROLE_USER,
            'status'            => User::STATUS_ACTIVE,
            'remember_token'    => Str::random(10),
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
