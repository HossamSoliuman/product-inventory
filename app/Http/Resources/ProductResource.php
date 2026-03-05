<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=> $this->id,
			'sku' => $this->sku,
			'name' => $this->name,
			'description' => $this->description,
			'price' => $this->price,
			'stock_quantity' => $this->stock_quantity,
			'low_stock_threshold' => $this->low_stock_threshold,
			'status' => $this->status,
            'created_at' => $this->created_at,
            'last_update' => $this->updated_at,
        ];
    }
}
