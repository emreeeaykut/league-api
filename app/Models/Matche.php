<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matche extends Model
{
    use HasFactory;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'home_team_result',
        'away_team_result',
        'is_over',
        'rank'
    ];

    public function home_team()
    {
        return $this->belongsTo(Team::class);
    }

    public function away_team()
    {
        return $this->belongsTo(Team::class);
    }
}
