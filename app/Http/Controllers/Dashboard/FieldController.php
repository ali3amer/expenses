<?php

namespace App\Http\Controllers\Dashboard;

use App\Field;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->select == 'all') {
            return Field::all()->keyBy('id');
        } elseif ($request->search != '' && $request->table != '') {
            return Field::with('table')->where('table_id', $request->table)->where('name', 'LIKE', '%' . $request->search . '%')->paginate(10);
        } elseif($request->search != '') {
            return Field::with('table')->where('name', 'LIKE', '%' . $request->search . '%')->paginate(10);
        } elseif($request->table != '') {
            return Field::with('table')->where('table_id', $request->table)->paginate(10);
        } else {
            return Field::with('table')->paginate(10);
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
            'name'  =>  'required|unique:fields|string|max:191',
            'table_id' => 'required'
        ]);

        return Field::create([
            'name'  =>  $request['name'],
            'table_id'  =>  $request['table_id']
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function show(Field $field)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function edit(Field $field)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Field $field)
    {
        $this->validate($request, [
            'name'  =>  'required|string|max:191|unique:fields,name,'.$field->id,
            'table_id' => 'required'
        ]);
        $field->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Field  $field
     * @return \Illuminate\Http\Response
     */
    public function destroy(Field $field)
    {
        $field->delete();
    }
}
