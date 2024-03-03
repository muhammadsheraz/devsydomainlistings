<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
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
            'domain' => ['required', 'string', 'max:255'],
            'exists_since' => ['required', 'string','date_format:Y'],
            'starting_date' => ['required', 'date_format:Y-m-d G:i:s|after_or_equal:now',],
            'ending_date' => ['required', 'date_format:Y-m-d G:i:s|after:starting_date',],
            'min_bid_increment' => ['nullable', 'integer'],
            'starting_price' => ['nullable', 'integer'],
            'target_price' => ['nullable', 'integer'],
        ];
    }
}
