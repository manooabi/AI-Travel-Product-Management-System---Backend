<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'destination' => $this->destination,
            'category' => $this->category,
            'description' => $this->description,

            'highlights' => $this->highlights,
            'inclusions' => $this->inclusions,
            'tags' => $this->tags,

            'price' => $this->price,
            'inventory_count' => $this->inventory_count,

            'valid_from' => $this->valid_from?->toISOString(),
            'valid_until' => $this->valid_until?->toISOString(),

            'status' => $this->status,

            'is_expired' => $this->valid_until?->isPast() ?? false,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
