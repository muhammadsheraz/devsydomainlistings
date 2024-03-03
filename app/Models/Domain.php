<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\DomainStatus;
use App\Enums\DomainDepositType;

class Domain extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'domain', 'exists_since', 'starting_date', 'ending_date', 'target_price',
        'minimum_bid_increment', 'starting_price', 'status','deposit_type', 'deposit_amount'
    ];

    /**
     * Domain statuses and deposit type attributes to be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => DomainStatus::class,
        'deposit_type' => DomainDepositType::class,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => DomainStatus::UPCOMING,
        'deposit_type' => DomainDepositType::FIXED,
    ];
}
