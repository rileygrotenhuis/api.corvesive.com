<?php

namespace App\Events;

use App\Models\Paystub;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaystubModified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Paystub $paystub
    ) {
        //
    }
}
