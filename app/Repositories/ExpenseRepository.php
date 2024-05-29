<?php

namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ExpenseRepository
{
    public function __construct(protected User $user)
    {
        //
    }

    /**
     * Returns all of a user's monthly
     * expense records.
     */
    public function all(): Collection
    {
        return $this->user->expenses;
    }

    /**
     * Returns all of a user's monthly expenses
     * for the next 12 months grouped together
     * by the month.
     */
    public function monthly(): Collection
    {
        $startDate = now()->startOfMonth();
        $endDate = now()->addMonths(11)->endOfMonth();

        return $this->user->monthlyExpenses()
            ->selectRaw('*, DATE_FORMAT(due_date, \'%m-%Y\') as monthYear')
            ->with('expense')
            ->where('due_date', '>=', $startDate)
            ->where('due_date', '<=', $endDate)
            ->get()
            ->groupBy('monthYear');
    }

    /**
     * Returns an array of month selection options
     * for the user's monthly expenses.
     */
    public function getMonthlySelectionOptions(Collection $monthlyExpenses): Collection
    {
        return $monthlyExpenses->keys()->map(function ($date) {
            $month = Str::before($date, '-');
            $year = Str::after($date, '-');

            return [
                'value' => $date,
                'label' => Carbon::createFromDate($year, $month)->format('F Y'),
            ];
        });
    }
}