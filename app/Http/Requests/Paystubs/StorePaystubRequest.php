<?php

namespace App\Http\Requests\Paystubs;

use App\Traits\PaystubRecurrence;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ConditionalRules;
use Illuminate\Validation\Rule;

class StorePaystubRequest extends FormRequest
{
    use PaystubRecurrence;

    public function rules(): array
    {
        return [
            'issuer' => ['required', 'string', 'max:255'],
            'amount_in_cents' => ['required', 'integer', 'min:0'],
            'recurrence_rate' => ['required', 'string', Rule::in(self::$RECURRENCE_RATES)],
            'recurrence_interval_one' => ['required', $this->intervalRules()],
            'recurrence_interval_two' => [
                'nullable',
                'required_if:recurrence_rate,bi-monthly,bi-weekly',
                $this->intervalRules(),
            ],
        ];
    }

    protected function intervalRules(): ConditionalRules
    {
        return Rule::when(
            in_array($this->recurrence_rate, ['weekly', 'bi-weekly']),
            ['integer', 'between:1,7'],
            ['integer', 'between:1,32']
        );
    }

    public function messages(): array
    {
        return [
            'recurrence_interval_one.between' => 'Your selected interval is out of range.',
            'recurrence_interval_two.between' => 'Your selected interval is out of range.',
            'recurrence_interval_two.required_if' => 'You must provide a second value for bi-weekly or bi-montly paystubs.',
        ];
    }
}
