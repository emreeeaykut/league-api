<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MatcheResource;
use App\Http\Resources\PointResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponser;
use App\Models\Team;
use App\Http\Resources\TeamResource;
use App\Models\Matche;
use App\Models\Point;

class TeamController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Team::all();

        return $this->success(TeamResource::collection($data), 'Records fetched successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:teams,name',
            'defense' => 'required|integer|min:0'
        ]);

        if ($validator->fails()){
            return $this->error('Validation error', 400, $validator->errors());
        }

        if ($request->offence > 100) {
            return $this->error('Validation error', 400, 'Data cannot be greater than 100');
        }

        $team = new Team();

        $team->name = $request->name;
        $team->offence = $request->offence;
        $team->defense = $request->defense;

        $team->save();

        return $this->success(new TeamResource($team), 'Created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Data not found');
        }

        return $this->success(new TeamResource($team), 'Record fetched successfully');
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
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Data not found');
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'defense' => 'required|integer|min:0'
        ]);

        if ($validator->fails()){
            return $this->error('Validation error', 400, $validator->errors());
        }

        if ($request->offence > 100) {
            return $this->error('Validation error', 400, 'Data cannot be greater than 100');
        }

        $team->name = $request->name;
        $team->offence = $request->offence;
        $team->defense = $request->defense;

        $team->save();

        return $this->success(new TeamResource($team), 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Data not found');
        }

        $team->delete();

        return $this->success(null, 'Deleted successfully');
    }

    /**
     * Display the specified point resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function pointsShow(int $id, int $pointId)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Team data not found');
        }

        $point = Point::find($pointId);

        if (is_null($point)) {
            return $this->error('Point data not found');
        }

        return $this->success(new PointResource($point), 'Team record fetched successfully');
    }

    /**
     * Store a newly created point resource in storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function pointStore(int $id)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Team data not found');
        }

        $point = new Point();

        $point->team_id = $team->id;

        $point->save();

        return $this->success(new PointResource($point), 'Point created successfully');
    }

    /**
     * Update the specified point resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @param int $pointId
     * @return \Illuminate\Http\Response
     */
    public function pointUpdate(Request $request, int $id, int $pointId)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Data not found');
        }

        $point = Point::find($pointId);

        if (is_null($point)) {
            return $this->error('Point data not found');
        }

        $validator = Validator::make($request->all(),[
            'scored_goal' => 'required|numeric',
            'conceded_goal' => 'required|numeric',
        ]);

        if ($validator->fails()){
            return $this->error('Validation error', 400, $validator->errors());
        }

        $played = 1;
        $won = 0;
        $drawn = 0;
        $lost = 0;
        $points = 0;

        if ($request->scored_goal > $request->conceded_goal) {
            $won = 1;
            $points = 3;
        } else if ($request->scored_goal == $request->conceded_goal) {
            $drawn = 1;
        } else if ($request->scored_goal < $request->conceded_goal) {
            $lost = 1;
        }

        $point->team_id = $team->id;
        $point->played = $point->played + $played;
        $point->won = $point->won + $won;
        $point->drawn = $point->drawn + $drawn;
        $point->lost = $point->lost + $lost;
        $point->gf = $point->gf + $request->scored_goal;
        $point->ga = $point->ga + $request->conceded_goal;
        $point->gd = $point->gd + ($request->scored_goal - $request->conceded_goal);
        $point->points = $point->points + $points;

        $point->save();

        return $this->success(new PointResource($point), 'Updated successfully');
    }

    /**
     * Remove the specified point resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function pointDestroy(int $id, int $pointId)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Team not found');
        }

        $point = Point::find($pointId);

        if (is_null($point)) {
            return $this->error('Point data not found');
        }

        $point->delete();

        return $this->success(null, 'Point deleted successfully');
    }

    /**
     * Bring the next match.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function nextMatche(int $id)
    {
        $team = Team::find($id);

        if (is_null($team)) {
            return $this->error('Team not found');
        }

        $matche = Matche::where('is_over', 0)
                ->where(function($q) use ($id) {
                    $q->where('home_team_id', $id)->orWhere('away_team_id', $id);
                })
                ->orderBy('rank', 'ASC')
                ->first();

        if (is_null($matche)) {
            return $this->error('Matche not found');
        }

        return $this->success(new MatcheResource($matche), 'Matche record fetched successfully');
    }
}
