<?php

namespace App\Http\Controllers\Dashboard;

use App\Cash;
use App\Crop;
use App\Http\Controllers\Controller;
use App\Visit;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->client_id != '') {
            return Visit::where('client_id', $request->client_id)->paginate(10);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'client_id'  =>  'required',
            'field_id' => 'required',
            'type' => 'required',
        ]);

        $visit = Visit::create([
            'client_id'  =>  $request['client_id'],
            'field_id' => $request['field_id'],
            'type' => $request['type']
        ]);

        if ($visit->type == 1) {
            Cash::create([
                'visit_id' => $visit->id,
                'amount' => $request->quantity
            ]);
        } elseif ($visit->type == 2) {
            Crop::create([
                'visit_id' => $visit->id,
                'crop_id' => $request->crop_id,
                'quantity' => $request->quantity
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function show(Visit $visit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function edit(Visit $visit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visit $visit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Visit  $visit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visit $visit)
    {
        //
    }
}
