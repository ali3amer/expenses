<?php

namespace App\Http\Controllers\Dashboard;

use App\Delegate;
use App\Http\Controllers\Controller;
use App\User;
use App\User_Power;
use App\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user == 'all') {
            return User::all();
        } else {
            return User::with('user_power')->paginate(10);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        return view('dashboard.users.create');
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
            'name'  =>  'required|string|max:191|unique:users',
            'password'  =>  'required|string|min:6',
            'state_id'  =>  'required',
            'power'  =>  'required'
        ]);

        $user = User::create([
            'name'  =>  $request['name'],
            'password'  =>  Hash::make($request['password']),
            'state_id'  =>  $request['state_id'],
            'power'  =>  $request['power'],
        ]);

        if ($user->power == 'town') {
            User_Power::create([
                'user_id' => $user->id,
                'town_id' => $request->town_id
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

        return $user->with(['zones', 'delegates', 'books'])->get();

//        dd(User::first());
//        return $user->zones()->get();
//        $languages_levels = [1 => ['delegate_id' => 3]];
//        dd($user->delegates()->sync($languages_levels));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name'  =>  'required|string|max:191|unique:users,name,'.$user->id,
            'password'  =>  'sometimes|string|min:6',
            'state_id'  =>  'required',
            'power'  =>  'required'
        ]);

        $user->update([
            'name'  =>  $request['name'],
            'password'  =>  Hash::make($request['password']),
            'state_id'  =>  $request['state_id'],
            'power'  =>  $request['power'],
        ]);

        User_Power::where('user_id', $user->id)->delete();

        if ($request->power == 'town') {
            User_Power::create([
                'user_id' => $user->id,
                'town_id' => $request->town_id
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
    }
}
