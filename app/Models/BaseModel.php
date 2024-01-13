<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BaseModel extends Model
{
    public static function boot()
    {
        parent::boot();

        if ($user = Auth::user()) {
            static::creating(function ($model) use ($user) {
                if (self::hasColumn($model->table, 'created_by')) {
                    $model->created_by = $user->id;
                }
                if (self::hasColumn($model->table, 'updated_by')) {
                    $model->updated_by = $user->id;
                }
            });

            static::updating(function ($model) use ($user) {
                if (self::hasColumn($model->table, 'updated_by')) {
                    $model->updated_by = $user->id;
                }
                $model->updated_at = Carbon::now();
            });

            static::saving(function ($model) use ($user) {
                if (self::hasColumn($model->table, 'updated_by')) {
                    $model->updated_by = $user->id;
                }
                $model->updated_at = Carbon::now();
            });
        }
    }

    protected static function hasColumn(string $table, string $column): bool
    {
        return Schema::connection(config('database.default'))->hasColumn($table, $column);
    }
}
