<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['sometimes', 'integer', 'min:0'],
            'status' => ['nullable', 'string', 'in:' . implode(',', [
                Product::STATUS_ACTIVE,
                Product::STATUS_INACTIVE,
                Product::STATUS_DISCONTINUE,
            ])],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'status' => $this->status ?? Product::STATUS_ACTIVE,
        ]);
    }
}
