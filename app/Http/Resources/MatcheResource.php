<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MatcheResource extends JsonResource
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
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'home_team_result' => $this->home_team_result,
            'away_team_result' => $this->away_team_result,
            'is_over' => $this->is_over,
            'rank' => $this->rank,
            'home_team' => $this->home_team,
            'away_team' => $this->away_team,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
