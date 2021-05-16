<?php

namespace App\Http\Controllers\Dashboard;

use App\Cash;
use App\Crop;
use App\Http\Controllers\Controller;
use App\Program;
use App\Unit;
use App\Zone;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->select == 'all') {
            return Unit::all();
        } elseif ($request->town != null) {
            return Unit::with('town')->where('town_id', $request->town)->paginate(10);
        } else {
            return Unit::with('town')->paginate(10);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:units|string|max:191',
            'town_id' => 'required|integer'
        ]);
        return Unit::create([
            'name' => $request['name'],
            'town_id' => $request['town_id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Unit $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit, Request $request)
    {
        if ($request->program != '') {
            $zones = Zone::where('unit_id', $unit->id)->get()->keyBy('id');
            $data = [];
            $program = Program::where('id', $request->program)->first();
            if ($program['type'] == 1) {
                foreach ($zones as $zone) {
                    $data[$zone->id] = Cash::where('program_id', $request->program)->where('zone_id', $zone->id)->with('client')->get()->keyBy('client_id');
                }
            } elseif ($program['type'] == 2) {
                foreach ($zones as $zone) {
                    $data[$zone->id] = Crop::where('program_id', $request->program)->where('zone_id', $zone->id)->with('client')->get()->keyBy('client_id');
                }
            }
            return $data;
        } else {
            return Zone::where('unit_id', $unit->id)->get()->keyBy('id');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Unit $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Unit $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:units,name,' . $unit->id,
            'town_id' => 'required|integer'
        ]);
        $unit->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Unit $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
    }
}
