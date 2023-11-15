<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Saving extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'savings';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payPeriods(): BelongsToMany
    {
        return $this->belongsToMany(PayPeriod::class, 'pay_period_saving');
    }
}
