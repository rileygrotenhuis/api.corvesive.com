<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePayPeriodBillsRequest;
use App\Models\PayPeriodBill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PayPeriodBillController extends Controller
{
    public function index(Request $request): Response
    {
        $currentPayPeriod = $request->user()->currentPayPeriod;

        $bills = PayPeriodBill::query()
            ->with('bill')
            ->where('pay_period_id', $currentPayPeriod->id)
            ->get();

        return Inertia::render('PayPeriods/Bills/Index', [
            'bills' => $bills,
        ]);
    }

    public function settings(Request $request): Response
    {
        return Inertia::render('PayPeriods/Bills/Settings', [
            'bills' => $request->user()->monthlyBills,
            'currentBills' => $request->user()->currentPayPeriod->bills,
        ]);
    }

    public function store(StorePayPeriodBillsRequest $request): RedirectResponse
    {
        PayPeriodBill::updateOrCreate(
            [
                'user_id' => $request->user()->id,
                'pay_period_id' => $request->user()->currentPayPeriod->id,
                'bill_id' => $request->input('bill_id'),
            ],
            [
                'amount_in_cents' => $request->input('amount') * 100,
                'due_date' => $request->input('due_date'),
            ]
        );

        return to_route('pay-period-bills.settings');
    }

    public function destroy(PayPeriodBill $payPeriodBill): RedirectResponse
    {
        $payPeriodBill->delete();

        return to_route('pay-period-bills.settings');
    }
}
