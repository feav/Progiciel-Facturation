<?php

namespace App\Http\Controllers;

use App\User;
use App\Role;
use Illuminate\Http\Request;
use App\RESTResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OthersResponses\UserResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        $users->transform(function ($item, $key) {
            $user = new UserResponse
                        (
                            $item->id, 
                            $item->name,
                            $item->email,
                            $item->password, 
                            Role::find($item->role_id),
                            date('d-m-Y à H:i:s', strtotime($item->created_at)),
                            User::find($item->cree_par)->name,
                            date('d-m-Y à H:i:s', strtotime($item->updated_at)),
                            User::find($item->modifie_par)->name
                        );
            return $user;
        });
        return response()->json(new RESTResponse(200, "OK", $users));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role()->associate(Role::find($request->input('role')));
        $user->cree_par = Auth::user()->id;
        $user->modifie_par = Auth::user()->id;
        $user->save();
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
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($user != null){
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            if($request->has('password'))
                $user->password = bcrypt($request->input('password'));
            $user->role()->dissociate();
            $user->role()->associate(Role::find($request->input('role')));
            $user->modifie_par = Auth::user()->id;
            $user->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaitez modifier n'existe pas dans la Base de données !", null));
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
        $user->role()->dissociate();
        $user->delete();
        return response()->json(new RESTResponse(200, "OK", null));
    }
}
