<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddDomainRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'domain' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!filter_var($value, FILTER_VALIDATE_DOMAIN)) {
                    $fail('The ' . $attribute . ' is not a valid domain name.');
                }
            }],
            'exists_since' => ['date_format:Y'],
            'starting_date' => ['required', 'date_format:Y-m-d H:i:s'],
            'ending_date' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:starting_date'],
            'target_price' => ['required', 'numeric'],
            'min_bid_increment' => ['required', 'numeric'],
            'starting_price' => ['required', 'numeric'],
            'deposit_amount' => ['required', 'numeric'],
        ];
    }
}
