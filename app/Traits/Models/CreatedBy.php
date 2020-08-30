<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 18.01.18
 * Time: 13:05
 */

namespace App\Traits\Models;

use Illuminate\Support\Facades\Auth;

trait CreatedBy
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $userId = Auth::user()->id;

            $model->created_by = $userId;

            if (isset($model->updated_by)) {
                $model->updated_by = $userId;
            }
        });
    }
}
