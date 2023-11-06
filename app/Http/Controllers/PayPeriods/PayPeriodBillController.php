<?php

namespace App\Http\Controllers\PayPeriods;

use App\Exceptions\AlreadyAttachedToPayPeriod;
use App\Http\Controllers\Controller;
use App\Http\Requests\PayPeriods\StorePayPeriodBillRequest;
use App\Http\Requests\PayPeriods\UpdatePayPeriodBillRequest;
use App\Http\Resources\PayPeriods\PayPeriodResource;
use App\Models\Bill;
use App\Models\PayPeriod;
use App\Services\PayPeriods\PayPeriodBillService;

class PayPeriodBillController extends Controller
{
    public function store(StorePayPeriodBillRequest $request, PayPeriod $payPeriod, Bill $bill): PayPeriodResource
    {
        $this->authorize('bill', [
            $payPeriod,
            $bill,
        ]);

        if ((new PayPeriodBillService())->billIsAlreadyAttachedToPayPeriod($payPeriod, $bill)) {
            throw new AlreadyAttachedToPayPeriod();
        }

        (new PayPeriodBillService())
            ->addBillToPayPeriod(
                $payPeriod->id,
                $bill->id,
                $request->amount,
                $request->due_date
            );

        return new PayPeriodResource(
            $payPeriod->load([
                'paystubs',
                'bills',
                'budgets',
            ])
        );
    }

    public function update(UpdatePayPeriodBillRequest $request, PayPeriod $payPeriod, Bill $bill): PayPeriodResource
    {
        $this->authorize('bill', [
            $payPeriod,
            $bill,
        ]);

        (new PayPeriodBillService())
            ->updatePayPeriodBill(
                $payPeriod->id,
                $bill->id,
                $request->amount,
                $request->due_date,
            );

        return new PayPeriodResource(
            $payPeriod->load([
                'paystubs',
                'bills',
                'budgets',
            ])
        );
    }

    public function destroy(PayPeriod $payPeriod, Bill $bill): PayPeriodResource
    {
        $this->authorize('bill', [
            $payPeriod,
            $bill,
        ]);

        (new PayPeriodBillService())
            ->removeBillFromPayPeriod(
                $payPeriod->id,
                $bill->id
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