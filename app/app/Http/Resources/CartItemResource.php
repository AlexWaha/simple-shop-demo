<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->relationLoaded('product')
            ? (new ProductResource($this->product))->resolve()
            : null;

        return [
            'id' => $this->id,
            'product' => $product,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'subtotal' => $this->subtotal,
            'formatted_subtotal' => number_format($this->subtotal, 2),
        ];
    }
}
