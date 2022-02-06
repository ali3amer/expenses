<?php

namespace App\Http\Controllers\Dashboard;

use App\Client;
use App\Crop;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CropController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->editMode != '' && $request->zone_id != '' && $request->program_id != '') {
            return Crop::where('zone_id', $request->zone_id)->where('program_id', $request->program_id)->first();
        } elseif ($request->client_id != '') {
            return Crop::where('client_id', $request->client_id)->latest()->paginate(10);
        } elseif ($request->zone_id != '' && $request->program_id != '') {
            $data = [];
            $clients = Crop::where('zone_id', $request->zone_id)->where('program_id', $request->program_id)->with('client')->get();
            foreach ($clients as $client) {
                $data[$client->client->id] = $client->client;
            }
            return $data;
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
            'field_id'  =>  'required',
            'zone_id'  =>  'required',
            'crop_id'  =>  'required',
            'quantity'  =>  'required'
        ]);

        if ($request->program != '') {
            $program = $request->program;
        } else {
            $program = null;
        }
        if ($request->editSupportMode == true) {
            Crop::where('program_id', $program)->where('zone_id', $request->zone_id)->delete();
        }

        foreach ($request->clients as $client) {
            Crop::create([
                'client_id' => $program == null ? $client['id'] : $client ,
                'field_id' => $request['field_id'],
                'zone_id' => $request['zone_id'],
                'crop_id' => $request['crop_id'],
                'quantity' => $request['quantity'],
                'program_id' => $program,
                'created_at' => $request['date'] . ' ' . date('H:i:s')
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Crop  $crop
     * @return \Illuminate\Http\Response
     */
    public function show(Crop $crop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Crop  $crop
     * @return \Illuminate\Http\Response
     */
    public function edit(Crop $crop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Crop  $crop
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Crop $crop)
    {
        $this->validate($request, [
            'field_id'  =>  'required',
            'crop_id'  =>  'required',
            'quantity'  =>  'required',
        ]);

        $crop->update([
            'field_id' => $request['field_id'],
            'crop_id' => $request['crop_id'],
            'quantity' => $request['quantity'],
            'created_at' => $request['date'] . ' ' . date('H:i:s')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Crop  $crop
     * @return \Illuminate\Http\Response
     */
    public function destroy(Crop $crop)
    {
        $crop->delete();
    }
}
