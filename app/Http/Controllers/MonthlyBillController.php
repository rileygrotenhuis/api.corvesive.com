<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMonthlyBillRequest;
use App\Http\Requests\UpdateMonthlyBillRequest;
use App\Models\MonthlyBill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MonthlyBillController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Monthly/Bills/Index', [
            'monthlyBills' => $request->user()->monthlyBills,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Monthly/Bills/Create');
    }

    public function store(StoreMonthlyBillRequest $request): RedirectResponse
    {
        $request->user()->monthlyBills()->create([
            'issuer' => $request->input('issuer'),
            'name' => $request->input('name'),
            'amount_in_cents' => $request->input('amount') * 100,
            'due_day_of_month' => $request->input('due_day_of_month'),
            'notes' => $request->input('notes'),
        ]);

        return to_route('monthly.bills.index');
    }

    public function show(MonthlyBill $monthlyBill): Response
    {
        $this->authorize('isOwner', $monthlyBill);

        return Inertia::render('Monthly/Bills/Show', [
            'monthlyBill' => $monthlyBill,
        ]);
    }

    public function update(UpdateMonthlyBillRequest $request, MonthlyBill $monthlyBill): RedirectResponse
    {
        $this->authorize('isOwner', $monthlyBill);

        $monthlyBill->update([
            'issuer' => $request->input('issuer'),
            'name' => $request->input('name'),
            'amount_in_cents' => $request->input('amount') * 100,
            'due_day_of_month' => $request->input('due_day_of_month'),
            'notes' => $request->input('notes'),
        ]);

        return to_route('monthly.bills.show', $monthlyBill);
    }

    public function destroy(): RedirectResponse
    {
        //
    }
}
