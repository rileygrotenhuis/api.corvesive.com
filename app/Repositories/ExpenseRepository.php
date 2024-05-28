<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class ExpenseRepository
{
    public function __construct(protected User $user)
    {
        //
    }

    /**
     * Returns all of a user's monthly expense records.
     */
    public function all(): Collection
    {
        return $this->user->expenses;
    }

    /**
     * Returns all of a user's monthly expenses that are
     * due within the next 7 days.
     */
    public function upcoming(): Collection
    {
        return $this->user
            ->monthlyExpenses()
            ->with('expense')
            ->where('due_date', '>=', now()->format('Y-m-d'))
            ->where('due_date', '<=', now()->addDays(7)->format('Y-m-d'))
            ->get();
    }

    /**
     * Returns all of a user's monthly expenses that are
     * due within the current calendar month.
     */
    public function thisMonth(): Collection
    {
        $today = now();

        return $this->user
            ->monthlyExpenses()
            ->with('expense')
            ->where('year', $today->year)
            ->where('month', $today->month)
            ->get();
    }

    /**
     * Returns all of a user's monthly expenses that are
     * due during next calendar month.
     */
    public function nextMonth(): Collection
    {
        $nextMonth = now()->addMonth();

        return $this->user
            ->monthlyExpenses()
            ->with('expense')
            ->where('year', $nextMonth->year)
            ->where('month', $nextMonth->month)
            ->get();
    }
}
