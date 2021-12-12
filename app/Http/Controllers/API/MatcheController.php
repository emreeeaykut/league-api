<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Matche;
use App\Http\Resources\MatcheResource;
use App\Models\Point;
use App\Traits\ApiResponser;

class MatcheController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Matche::orderBy('rank', 'ASC')->get();

        return $this->success(MatcheResource::collection($data), 'Records fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()){
            return $this->error('Validation error', 400, $validator->errors());
        }

        $matche = new Matche();

        $matche = $this->setData($matche, $request);

        $matche->save();

        return $this->success(new MatcheResource($matche), 'Created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $matche = Matche::find($id);

        if (is_null($matche)) {
            return $this->error('Data not found');
        }

        return $this->success(new MatcheResource($matche), 'Record fetched successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $matche = Matche::find($id);

        if (is_null($matche)) {
            return $this->error('Data not found');
        }

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()){
            return $this->error('Validation error', 400, $validator->errors());
        }

        $matche = $this->setData($matche, $request);

        $matche->save();

        return $this->success(new MatcheResource($matche), 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $matche = Matche::find($id);

        if (is_null($matche)) {
            return $this->error('Data not found');
        }

        $matche->delete();

        return $this->success(null, 'Deleted successfully');
    }

    /**
     * Predict the match and process the results.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function play(int $id)
    {
        $matche = Matche::find($id);

        if (is_null($matche)) {
            return $this->error('Data not found');
        }

        $matcheResource = new MatcheResource($matche);

        $scoreData = $this->scorePredictions($matcheResource);

        $matche->home_team_result = $scoreData['homeTeamResult'];
        $matche->away_team_result = $scoreData['awayTeamResult'];
        $matche->is_over = 1;

        $matche->save();

        $this->updateStandings(
            $matcheResource->home_team->id,
            $scoreData['homeTeamResult'],
            $scoreData['awayTeamResult']
        );

        $this->updateStandings(
            $matcheResource->away_team->id,
            $scoreData['awayTeamResult'],
            $scoreData['homeTeamResult']
        );

        return $this->success(new MatcheResource($matche), 'Match is over');
    }

    /**
     * Set validation rules.
     *
     * @return Array
     */
    private function validationRules()
    {
        return [
            'rank' => 'required|numeric',
            'home_team_id' => 'required|numeric',
            'away_team_id' => 'required|numeric',
        ];
    }

    /**
     * Set data.
     *
     * @param object $data
     * @param object $request
     * @return object $data
     */
    private function setData(object $data, object $request)
    {
        $data->rank = $request->rank;
        $data->home_team_id = $request->home_team_id;
        $data->away_team_id = $request->away_team_id;

        return $data;
    }

    /**
     * Match score predictions.
     *
     * @param MatcheResource $matche
     * @return Array
     */
    private function scorePredictions(MatcheResource $matche)
    {
        // team statistics (start)
        $homeTeamOffence = $matche->home_team->offence;
        $homeTeamDefense = $matche->home_team->defense;
        $homeTeamMotivation = $matche->home_team->motivation;
        $awayTeamOffence = $matche->away_team->offence;
        $awayTeamDefense = $matche->away_team->defense;
        $awayTeamMotivation = $matche->away_team->motivation;
        // team statistics (end)

        // who is strong in offence (start)
        $offence = 0;
        $offenceWinner = 'equal';

        if ($homeTeamOffence > $awayTeamOffence) {
            $offence = $homeTeamOffence - $awayTeamOffence;
            $offenceWinner = 'home';
        } else if ($homeTeamOffence < $awayTeamOffence) {
            $offence = $awayTeamOffence - $homeTeamOffence;
            $offenceWinner = 'away';
        }
        // who is strong in offence (end)

        // who is strong in defense (start)
        $defense = 0;
        $defenseWinner = 'equal';

        if ($homeTeamDefense > $awayTeamDefense) {
            $defense = $homeTeamDefense - $awayTeamDefense;
            $defenseWinner = 'home';
        } else if ($homeTeamDefense < $awayTeamDefense) {
            $offence = $awayTeamDefense - $homeTeamDefense;
            $defenseWinner = 'away';
        }
        // who is strong in defense (end)

        // who has the better motivation (start)
        $motivationWinner = 'equal';

        if ($homeTeamMotivation > $awayTeamMotivation) {
            $motivationWinner = 'home';
        } else if ($homeTeamMotivation < $awayTeamMotivation) {
            $motivationWinner = 'away';
        }
        // who has the better motivation (end)

        // Predicting matches based on statistics (start)
        $homeTeamResult = 0;
        $awayTeamResult = 0;

        if ($offenceWinner == 'home' && $defenseWinner == 'home') {
            if ($offence > 50 && $defense > 50) {
                $homeTeamResult = rand(5, 9);
                $awayTeamResult = rand(0, 3);
            } else {
                $homeTeamResult = rand(2, 4);
                $awayTeamResult = rand(0, 1);
            }
        } elseif ($offenceWinner == 'away' && $defenseWinner == 'away') {
            if ($offence > 50 && $defense > 50) {
                $homeTeamResult = rand(0, 3);
                $awayTeamResult = rand(4, 9);
            } else {
                $homeTeamResult = rand(0, 1);
                $awayTeamResult = rand(2, 4);
            }
        } elseif ($offenceWinner == 'home' && $defenseWinner == 'away') {
            if ($offence > $defense) {
                $homeTeamResult = rand(2, 3);
                $awayTeamResult = rand(0, 1);
            } elseif ($offence < $defense) {
                $homeTeamResult = 0;
                $awayTeamResult = rand(1, 2);
            } else {
                $homeTeamResult = rand(0, 3);
                $awayTeamResult = $homeTeamResult;
            }
        } elseif ($offenceWinner == 'away' && $defenseWinner == 'home') {
            if ($offence > $defense) {
                $homeTeamResult = 0;
                $awayTeamResult = rand(1, 2);
            } elseif ($offence < $defense) {
                $homeTeamResult = rand(2, 3);
                $awayTeamResult = rand(0, 1);
            } else {
                $homeTeamResult = rand(0, 3);
                $awayTeamResult = $homeTeamResult;
            }
        } elseif ($offenceWinner == 'equal' && $defenseWinner == 'equal') {
            if ($motivationWinner == 'home') {
                $homeTeamResult = rand(2, 3);
                $awayTeamResult = rand(0, 1);
            } elseif ($motivationWinner == 'away') {
                $homeTeamResult = 0;
                $awayTeamResult = rand(1, 2);
            } elseif ($motivationWinner == 'equal') {
                $homeTeamResult = rand(0, 3);
                $awayTeamResult = $homeTeamResult;
            }
        }

        return [
            'homeTeamResult' => $homeTeamResult,
            'awayTeamResult' => $awayTeamResult
        ];
        // Predicting matches based on statistics (end)
    }

    /**
     * Update the standings.
     *
     * @param integer|null $teamId
     * @param integer|null $scored_goal
     * @param integer|null $conceded_goal
     * @return void
     */
    private function updateStandings(?int $teamId, ?int $scored_goal, ?int $conceded_goal)
    {
        $point = Point::where('team_id', $teamId)->first();

        $played = 1;
        $won = 0;
        $drawn = 0;
        $lost = 0;
        $points = 0;

        if ($scored_goal > $conceded_goal) {
            $won = 1;
            $points = 3;
        } else if ($scored_goal == $conceded_goal) {
            $drawn = 1;
            $points = 1;
        } else if ($scored_goal < $conceded_goal) {
            $lost = 1;
        }

        $point->played = $point->played + $played;
        $point->won = $point->won + $won;
        $point->drawn = $point->drawn + $drawn;
        $point->lost = $point->lost + $lost;
        $point->gf = $point->gf + $scored_goal;
        $point->ga = $point->ga + $conceded_goal;
        $point->gd = $point->gd + ($scored_goal - $conceded_goal);
        $point->points = $point->points + $points;

        $point->save();
    }
}
