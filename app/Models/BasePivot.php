<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class BasePivot extends Pivot
{
    protected bool $hasContributors = false;
    public $timestamps = false;

    public static function boot(): void
    {
        parent::boot();

        if ($user = Auth::user()) {
            static::creating(function ($model) use ($user) {
                if ($this->hasContributors) {
                    if (self::hasColumn($model->table, 'created_by')) {
                        $model->created_by = $user->id;
                    }
                    if (self::hasColumn($model->table, 'updated_by')) {
                        $model->updated_by = $user->id;
                    }
                }

                if ($this->timestamps) {
                    $model->created_at = Carbon::now();
                    $model->updated_at = Carbon::now();
                }
            });

            static::updating(function ($model) use ($user) {
                if ($this->hasContributors && self::hasColumn($model->table, 'updated_by')) {
                    $model->updated_by = $user->id;
                }

                if ($this->timestamps) {
                    $model->updated_at = Carbon::now();
                }
            });

            static::saving(function ($model) use ($user) {
                if ($this->hasContributors && self::hasColumn($model->table, 'updated_by')) {
                    $model->updated_by = $user->id;
                }

                if ($this->timestamps) {
                    $model->updated_at = Carbon::now();
                }
            });
        }
    }

    protected static function hasColumn(string $table, string $column): bool
    {
        return Schema::connection(config('database.default'))->hasColumn($table, $column);
    }
}
