<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->relationLoaded('items')
            ? $this->items->map(fn ($item) => (new CartItemResource($item))->resolve())
            : [];

        return [
            'id' => $this->id,
            'items' => $items,
            'total' => $this->total,
            'formatted_total' => number_format($this->total, 2),
            'item_count' => $this->item_count,
        ];
    }
}
