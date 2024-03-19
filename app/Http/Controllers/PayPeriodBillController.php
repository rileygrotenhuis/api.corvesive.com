<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class PayPeriodBillController extends Controller
{
    public function index(): Response
    {
        //
    }

    public function store(): RedirectResponse
    {
        return to_route('pay-periods.settings');
    }
}