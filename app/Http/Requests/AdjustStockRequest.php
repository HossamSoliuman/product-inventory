<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdjustStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'action'   => ['required', 'string', Rule::in(['increment', 'decrement'])],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
