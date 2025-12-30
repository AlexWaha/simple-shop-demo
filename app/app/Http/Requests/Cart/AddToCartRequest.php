<?php

namespace App\Http\Requests\Cart;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = Product::find($this->product_id);
        $maxQuantity = $product ? $product->stock_quantity : 1;

        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$maxQuantity],
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.max' => 'The requested quantity exceeds available stock.',
        ];
    }
}
