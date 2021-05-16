<?php

namespace App\Http\Controllers\Dashboard;

use App\Field;
use App\Http\Controllers\Controller;
use App\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->search != '') {
            return Table::where('name', 'LIKE', '%' . $request->search . '%')->paginate(10);
        } elseif($request->select == 'all') {
            return Table::all()->keyBy('id');
        } else {
            return Table::paginate(10);
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
            'name'  =>  'required|unique:tables|string|max:191',
            'percentage'  =>  'required'
        ]);

        return Table::create([
            'name'  =>  $request['name'],
            'percentage'  =>  $request['percentage']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        return Field::where('table_id', $table->id)->get()->keyBy('id');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        $this->validate($request, [
            'name'  =>  'required|string|max:191|unique:tables,name,'.$table->id,
            'percentage'  =>  'required'
        ]);
        $table->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy(Table $table)
    {
        $table->delete();
    }
}
