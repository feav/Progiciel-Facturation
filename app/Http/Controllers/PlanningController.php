<?php

namespace App\Http\Controllers;

use App\Planning;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\Base;
use App\Campagne;
use App\Resultat;

class PlanningController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Planning::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $planning = new Planning;
        $resultat = new Resultat;
        $planning->volume = $request->input('volume');
        $planning->date_envoi = $request->input('date_envoi');
        $resultat->volume = $request->input('volume');
        $resultat->date_envoi = $request->input('date_envoi');
        $base = Base::find($request->input('base'));
        $campagne = Campagne::find($request->input('campagne'));
        $planning->base()->associate($base);
        $planning->campagne()->associate($campagne);
        $resultat->base()->associate($base);
        $resultat->campagne()->associate($campagne);
        $planning->routeur_id = $base->routeur_id;
        $planning->annonceur_id = $campagne->annonceur_id;
        $resultat->routeur_id = $base->routeur_id;
        $resultat->annonceur_id = $campagne->annonceur_id;
        $planning->save();
        $resultat->save();
        return response()->json(new RESTResponse(200, "OK", null));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Planning::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $planning = Planning::find('id',$id);
        if($planning != null){
            $planning->volume = $request->input('volume');
            $planning->date_envoi = $request->input('date_envoi');
            $planning->base()->dissociate();
            $planning->campagne()->dissociate();
            $base = Base::find($request->input('base'));
            $campagne = Campagne::find($request->input('campagne'));
            $planning->base()->associate($base);
            $planning->campagne()->associate($campagne);
            $planning->routeur_id = $base->routeur_id;
            $planning->annonceur_id = $campagne->annonceur_id;
            $planning->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaiter modifier n'existe pas dans la Base de données !", null));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $planning = Planning::find($id);
        if($planning != null){
            $planning->base()->dissociate();
            $planning->campagne()->dissociate();
            $planning->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
        return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
