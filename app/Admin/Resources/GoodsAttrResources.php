<?php

namespace App\Admin\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GoodsAttrResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'values' => GoodsAttrValueResources::collection($this->whenLoaded('values')),
        ];
    }
}
