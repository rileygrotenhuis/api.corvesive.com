<?php

namespace App\Http\Controllers\Transactions;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class DepositController extends Controller
{
    /**
     * Refunds a deposit that
     * a user has made.
     */
    public function destroy(Deposit $deposit): RedirectResponse
    {
        Gate::authorize('isOwner', $deposit);

        $monthlyPaystub = $deposit->monthlyPaystub;

        $deposit->refund();

        return back();
    }
}
