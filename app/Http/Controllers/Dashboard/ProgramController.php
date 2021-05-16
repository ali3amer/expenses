<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->select == 'all') {
            return Program::all();
        } elseif ($request->type != '' && $request->year != '') {
            if ($request->type == '1') {
                $type = 'cashes';
            } elseif ($request->type == '2') {
                $type = 'crops';
            }
            return Program::where([['type', '=', $request->type], ['year', '=', $request->year]])->get();
        } elseif ($request->zone_id != '') {
            return Program::where('year', $request->year)->where('type', $request->type)->get()->keyBy('id');
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
            'name' => 'required|unique:programs|string|max:191',
            'year' => 'required',
            'type' => 'required',
        ]);

        return Program::create([
            'name' => $request['name'],
            'year' => $request['year'],
            'type' => $request['type']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Program $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Program $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Program $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191|unique:states,name,' . $program->id,
            'year' => 'required',
            'type' => 'required',
        ]);
        $program->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Program $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $program->delete();
    }
}
