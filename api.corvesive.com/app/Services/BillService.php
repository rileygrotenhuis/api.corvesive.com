<?php

namespace App\Services;

use App\Models\Bill;

class BillService
{
    public function createBill(
        int $userId,
        string $issuer,
        string $name,
        int $amount,
        int $dueDate,
        ?string $notes
    ): Bill {
        $bill = new Bill();
        $bill->user_id = $userId;
        $bill->issuer = $issuer;
        $bill->name = $name;
        $bill->amount = $amount;
        $bill->due_date = $dueDate;
        $bill->notes = $notes;
        $bill->save();

        return $bill;
    }

    public function updateBill(
        Bill $bill,
        string $issuer,
        string $name,
        int $amount,
        int $dueDate,
        ?string $notes
    ): Bill {
        $bill->issuer = $issuer;
        $bill->name = $name;
        $bill->amount = $amount;
        $bill->due_date = $dueDate;
        $bill->notes = $notes;
        $bill->save();

        return $bill;
    }

    public function deleteBill(Bill $bill): bool
    {
        return $bill->delete();
    }
}