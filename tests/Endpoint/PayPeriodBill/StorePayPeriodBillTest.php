<?php

namespace Tests\Endpoint\PayPeriodBill;

use App\Models\Bill;
use App\Models\PayPeriod;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class StorePayPeriodBillTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected PayPeriod $payPeriod;

    protected Bill $bill;

    protected string $dueDate;

    protected array $payload;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->authenticatesUser($this->user);

        $this->payPeriod = PayPeriod::factory()
            ->for($this->user)
            ->create();

        $this->bill = Bill::factory()
            ->for($this->user)
            ->create([
                'amount' => 100000,
            ]);

        $this->dueDate = Carbon::today()->addDays(5)->toDateString();

        $this->payload = [
            'amount' => 100000,
            'due_date' => $this->dueDate,
        ];
    }

    public function test_successful_pay_period_to_bill_link(): void
    {
        $this->submitRequest($this->bill)
            ->assertStatus(200);

        $this->assertDatabaseHas('pay_period_bill', [
            'pay_period_id' => $this->payPeriod->id,
            'bill_id' => $this->bill->id,
            'amount' => 100000,
            'due_date' => $this->dueDate,
            'has_payed' => false,
        ]);
    }

    public function test_successful_pay_period_to_bill_link_with_partial_amount(): void
    {
        $this->payload['amount'] = 50000;

        $this->submitRequest($this->bill)
            ->assertStatus(200);

        $this->assertDatabaseHas('pay_period_bill', [
            'pay_period_id' => $this->payPeriod->id,
            'bill_id' => $this->bill->id,
            'amount' => 50000,
            'due_date' => $this->dueDate,
            'has_payed' => false,
        ]);
    }

    public function test_failed_pay_period_to_bill_link_with_invalid_amount_field(): void
    {
        $this->payload['amount'] = 'invalid';

        $this->submitRequest($this->bill)
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('amount');
    }

    public function test_failed_pay_period_to_bill_link_with_out_of_range_amount_field(): void
    {
        $this->payload['amount'] = -100;

        $this->submitRequest($this->bill)
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('amount');
    }

    public function test_failed_pay_period_to_bill_link_with_missing_due_date_field(): void
    {
        unset($this->payload['due_date']);

        $this->submitRequest($this->bill)
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('due_date');
    }

    public function test_failed_pay_period_to_bill_link_with_invalid_due_date_field(): void
    {
        $this->payload['due_date'] = 'invalid';

        $this->submitRequest($this->bill)
            ->assertStatus(422)
            ->assertJsonValidationErrorFor('due_date');
    }

    public function test_failed_pay_period_to_bill_link_with_failed_authorization(): void
    {
        $newUser = User::factory()->create();
        $this->authenticatesUser($newUser);

        $newBill = Bill::factory()
            ->for($newUser)
            ->create();

        $this->submitRequest($newBill)
            ->assertStatus(403);
    }

    protected function submitRequest(Bill $bill): TestResponse
    {
        return $this->postJson(
            route('pay-periods.bills.store', [
                $this->payPeriod,
                $bill,
            ]),
            $this->payload
        );
    }
}
