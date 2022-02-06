<?php

namespace App\Http\Controllers\Dashboard;

use App\Cash;
use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->editMode != '' && $request->zone_id != '' && $request->program_id != '') {
            $cashes = Cash::where('zone_id', $request->zone_id)->where('program_id', $request->program_id)->first();
            $clients = Cash:: where('zone_id', $request->zone_id)->where('program_id', $request->program_id)->get(['client_id'])->keyBy('client_id');
            return response()->json(['cashes' => $cashes, 'clients' => $clients]);
        } elseif ($request->client_id != '') {
            return Cash::where('client_id', $request->client_id)->latest()->paginate(10);
        } elseif ($request->zone_id != '' && $request->program_id != '') {
            $data = [];
            $clients = Cash::where('zone_id', $request->zone_id)->where('program_id', $request->program_id)->with('client')->get();
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
            'quantity'  =>  'required'
        ]);

        if ($request->program != '') {
            $program = $request->program;
        } else {
            $program = null;
        }

        if ($request->editSupportMode == true) {
            Cash::where('program_id', $program)->where('zone_id', $request->zone_id)->delete();
        }
        foreach ($request->clients as $client) {
            $visit = Cash::create([
                'client_id'  =>  $program == null ? $client['id'] : $client,
                'field_id' => $request['field_id'],
                'zone_id' => $request['zone_id'],
                'amount' => $request['quantity'],
                'program_id' => $program,
                'created_at' => $request['date'] . ' ' . date('H:i:s')
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function show(Cash $cash)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function edit(Cash $cash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cash $cash)
    {
        dd($request->group);
        $this->validate($request, [
            'field_id'  =>  'required',
            'quantity'  =>  'required',
            'created_at' => $request['date'] . ' ' . date('H:i:s')
        ]);


        $cash->update([
            'field_id' => $request['field_id'],
            'amount' => $request->quantity
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cash $cash)
    {
        $cash->delete();
    }
}
