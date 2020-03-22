<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\RESTResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
        $user=User::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'password'=>bcrypt($request->input('password'))
        ]);
        return response()->json(new RESTResponse(200, "OK", null));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
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
        User::where('id',$id)
            ->update([
                'name' => $request->input('name'),
                'email'=>$request->input('email'),
                'password'=> bcrypt($request->input('password'))
            ]);
        return response()->json(new RESTResponse(200, "OK", null));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user=User::find($id);
        $user->delete();
        return response()->json(new RESTResponse(200, "OK", null));
    }
}
