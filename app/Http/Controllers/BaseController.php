<?php

namespace App\Http\Controllers;

use App\Base;
use App\Resultat;
use App\Campagne;
use App\Routeur;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\Http\Controllers\OthersResponses\BaseOtherResponse;

class BaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(new RESTResponse(200, "OK", Base::all()));
    }

    /**
     * Display a listing of the resource for statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForStatistics()
    {
        $bases = Resultat::all();
        $bases->transform(function ($item, $key) {
            $base = new BaseOtherResponse
                        (
                            $item->id, 
                            Base::find($item->base_id)->nom,
                            Campagne::find($item->campagne_id)->remuneration,
                            $item->resultat,
                            Routeur::find($item->routeur_id)->prix * $item->volume
                        );
            return $base;
        });
        return response()->json(new RESTResponse(200, "OK", $bases));
    }

    /**
     * Display a listing of the resource by Routeur
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function indexByRouteurId($id)
    {
        return response()->json(new RESTResponse(200, "OK", Base::all()->where('routeur_id', $id)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $base = new Base;
        $base->nom = $request->input('nom');
        $base->routeur()->associate(Routeur::find($request->input('routeur')));
        $base->save();
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
        return response()->json(new RESTResponse(200, "OK", Base::find($id)));
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
        $base = Base::find('id',$id);
        if($base != null){
            $base->nom = $request->input('nom');
            $routeur = Routeur::find($request->input('routeur'));
            $base->routeur()->dissociate();
            $base->routeur()->associate($routeur);
            $base->save();
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
        $base = Base::find($id);
        if($base != null){
            $base->routeur()->dissociate();
            $base->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
        return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
