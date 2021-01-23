<?php

namespace App\Http\Controllers\Dashboard;

use App\Client;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Client::paginate(10);
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
            'name'  =>  'required|string|max:191',
            'nID'  =>  'required|unique:clients',
            'age'  =>  'required',
            'gender'  =>  'required',
            'health'  =>  'required',
            'phone'  =>  'required',
            'work'  =>  'required',
            'income'  =>  'required',
            'status'  =>  'required',
            'numberFamily'  =>  'required',
            'zone_id'  =>  'required',
        ]);

        return Client::create([
            'name'  =>  $request['name'],
            'nID'  =>  $request['nID'],
            'age'  =>  $request['age'],
            'gender'  =>  $request['gender'],
            'health'  =>  $request['health'],
            'phone'  =>  $request['phone'],
            'work'  =>  $request['work'],
            'income'  =>  $request['income'],
            'status'  =>  $request['status'],
            'numberFamily'  =>  $request['numberFamily'],
            'zone_id'  =>  $request['zone_id'],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $this->validate($request, [
            'name'  =>  'required|string|max:191',
            'nID'  =>  'required|unique:clients',
            'age'  =>  'required',
            'gender'  =>  'required',
            'health'  =>  'required',
            'phone'  =>  'required',
            'work'  =>  'required',
            'income'  =>  'required',
            'status'  =>  'required',
            'numberFamily'  =>  'required',
            'zone_id'  =>  'required',
        ]);

        $client->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
    }
}
