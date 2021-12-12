<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Point;
use App\Http\Resources\PointResource;
use App\Traits\ApiResponser;

class PointController extends Controller
{
    use ApiResponser;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Point::orderBy('points', 'DESC')->get();;

        return $this->success(PointResource::collection($data), 'Records fetched successfully');
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

        $point = new Point();

        $point = $this->setData($point, $request);

        $point->save();

        return $this->success(new PointResource($point), 'Created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $point = Point::find($id);

        if (is_null($point)) {
            return $this->error('Data not found');
        }

        return $this->success(new PointResource($point), 'Record fetched successfully');
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
        $point = Point::find($id);

        if (is_null($point)) {
            return $this->error('Data not found');
        }

        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()){
            return $this->error('Validation error', 400, $validator->errors());
        }

        $point = $this->setData($point, $request);

        $point->save();

        return $this->success(new PointResource($point), 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $point = Point::find($id);

        if (is_null($point)) {
            return $this->error('Data not found');
        }

        $point->delete();

        return $this->success(null, 'Deleted successfully');
    }

    /**
     * Set validation rules.
     *
     * @return Array
     */
    private function validationRules()
    {
        return [
            'team_id' => 'required|numeric',
            'played' => 'required|numeric',
            'won' => 'required|numeric',
            'drawn' => 'required|numeric',
            'lost' => 'required|numeric',
            'gf' => 'required|numeric',
            'ga' => 'required|numeric',
            'gd' => 'required|numeric',
            'points' => 'required|numeric'
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
        $data->team_id = $request->team_id;
        $data->played = $request->played;
        $data->won = $request->won;
        $data->drawn = $request->drawn;
        $data->lost = $request->lost;
        $data->gf = $request->gf;
        $data->ga = $request->ga;
        $data->gd = $request->gd;
        $data->points = $request->points;

        return $data;
    }
}
