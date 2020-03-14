<?php

namespace App\Http\Controllers;

use App\Routeur;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\Resultat;
use App\Campagne;
use App\Http\Controllers\OthersResponses\RouteurOtherResponse;

class RouteurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(new RESTResponse(200, "OK", Routeur::all()));
    }

    /**
     * Display a listing of the resource for statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForStatistics()
    {
        $routeurs = Resultat::all();
        $routeurs->transform(function ($item, $key) {
            $routeur = new RouteurOtherResponse
                        (
                            $item->id, 
                            Routeur::find($item->routeur_id)->nom, 
                            Routeur::find($item->routeur_id)->prix, 
                            $item->volume,
                            Campagne::find($item->campagne_id)->remuneration,
                            $item->resultat,
                            Routeur::find($item->routeur_id)->prix * $item->volume
                        );
            return $routeur;
        });
        return response()->json(new RESTResponse(200, "OK", $routeurs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Routeur::create(['nom'=>$request->input('nom'), 'prix'=>$request->input('prix')]);
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
        return response()->json(new RESTResponse(200, "OK", Routeur::find($id)));
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
        Routeur::where('id',$id)
                ->update([  
                    'nom'=>$request->input('nom'),
                    'prix'=>$request->input('prix')
                ]);
        return response()->json(new RESTResponse(200, "OK", null));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $routeur = Routeur::find($id);
        if($routeur != null){
            $routeur->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
