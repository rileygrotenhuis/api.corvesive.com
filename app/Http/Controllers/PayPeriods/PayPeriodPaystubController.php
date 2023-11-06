<?php

namespace App\Http\Controllers\PayPeriods;

use App\Exceptions\AlreadyAttachedToPayPeriod;
use App\Http\Controllers\Controller;
use App\Http\Resources\PayPeriods\PayPeriodResource;
use App\Models\PayPeriod;
use App\Models\Paystub;
use App\Services\PayPeriods\PayPeriodPaystubService;
use Illuminate\Http\Request;

class PayPeriodPaystubController extends Controller
{
    public function store(Request $request, PayPeriod $payPeriod, Paystub $paystub): PayPeriodResource
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $this->authorize('paystub', [
            $payPeriod,
            $paystub,
        ]);

        if ((new PayPeriodPaystubService())->paystubIsAlreadyAttachedToPayPeriod($payPeriod, $paystub)) {
            throw new AlreadyAttachedToPayPeriod();
        }

        (new PayPeriodPaystubService())
            ->addPaystubToPayPeriod(
                $payPeriod,
                $paystub,
                $request->amount
            );

        return new PayPeriodResource(
            $payPeriod->load([
                'paystubs',
                'bills',
                'budgets',
            ])
        );
    }

    public function update(Request $request, PayPeriod $payPeriod, Paystub $paystub): PayPeriodResource
    {
        $request->validate([
            'amount' => 'required|integer|min:1',
        ]);

        $this->authorize('paystub', [
            $payPeriod,
            $paystub,
        ]);

        (new PayPeriodPaystubService())
            ->updatePayPeriodPaystub(
                $payPeriod,
                $paystub,
                $request->amount
            );

        return new PayPeriodResource(
            $payPeriod->load([
                'paystubs',
                'bills',
                'budgets',
            ])
        );
    }

    public function destroy(PayPeriod $payPeriod, Paystub $paystub): PayPeriodResource
    {
        $this->authorize('paystub', [
            $payPeriod,
            $paystub,
        ]);

        (new PayPeriodPaystubService())
            ->removePaystubFromPayPeriod(
                $payPeriod,
                $paystub
            );

        return new PayPeriodResource(
            $payPeriod->load([
                'paystubs',
                'bills',
                'budgets',
            ])
        );
    }
}