<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'credit_accounts';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
