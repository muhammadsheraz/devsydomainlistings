<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TwoFactorAuth extends Model
{

    protected $dates = ['expires_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];
}
