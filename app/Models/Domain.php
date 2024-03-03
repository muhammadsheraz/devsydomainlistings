<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\DomainStatus;

class Domain extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'name', 'exists_since', 'starting_date', 'ending_date', 'target_amount',
        'minimum_bid_increment', 'starting_amount', 'status'
    ];

    /**
     * Domain statuses attributes to be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => DomainStatus::class,
    ];
}
