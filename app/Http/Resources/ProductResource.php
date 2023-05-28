<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\BrandResource;

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
                
            "id" => $this->id,
            "name" => $this->name,
            "upc" => $this->upc,
            "part_number" => $this->part_number,
            'brand_id' => new BrandResource($this->brand),
            'images' => new ImageCollection($this->images),
            'categories' => new CategoryCollection($this->categories),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
        ];
    }
}
