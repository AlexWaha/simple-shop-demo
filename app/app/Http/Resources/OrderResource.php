<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $items = $this->relationLoaded('items')
            ? $this->items->map(fn ($item) => (new OrderItemResource($item))->resolve())
            : [];

        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'total' => $this->total,
            'formatted_total' => number_format($this->total, 2),
            'items' => $items,
            'item_count' => $this->relationLoaded('items') ? $this->items->count() : 0,
            'created_at' => $this->created_at->toISOString(),
            'formatted_date' => $this->created_at->format('M d, Y H:i'),
        ];
    }
}
