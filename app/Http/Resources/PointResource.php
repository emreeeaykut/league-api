<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'played' => $this->played,
            'won' => $this->won,
            'drawn' => $this->drawn,
            'lost' => $this->lost,
            'gf' => $this->gf,
            'ga' => $this->ga,
            'gd' => $this->gd,
            'points' => $this->points,
            'team' => $this->team,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
