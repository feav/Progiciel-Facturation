<?php

namespace App\Http\Controllers;

use App\Campagne;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\Annonceur;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OthersResponses\CampagneOtherResponse;

class CampagneController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campagnes = Campagne::all();
        $campagnes->transform(function ($item, $key) {
            $campagne = new CampagneOtherResponse
                        (
                            $item->id, 
                            $item->nom,
                            $item->type_remuneration,
                            $item->remuneration, 
                            Annonceur::find($item->annonceur_id),
                            date('d-m-Y à H:i:s', strtotime($item->created_at)),
                            User::find($item->cree_par)->name,
                            date('d-m-Y à H:i:s', strtotime($item->updated_at)),
                            User::find($item->modifie_par)->name
                        );
            return $campagne;
        });
        return response()->json(new RESTResponse(200, "OK", $campagnes));
    }

    /**
     * Display a listing of the resource by Annonceur
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function indexByAnnonceurId($id)
    {
        return response()->json(new RESTResponse(200, "OK", Campagne::all()->where('annonceur_id', $id)));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $campagne = new Campagne;
        $campagne->nom = $request->input('nom');
        $campagne->type_remuneration = $request->input('type_remuneration');
        $campagne->remuneration = $request->input('remuneration');
        $annonceur = Annonceur::find($request->input('annonceur'));
        $campagne->annonceur()->associate($annonceur);
        $campagne->cree_par = Auth::user()->id;
        $campagne->modifie_par = Auth::user()->id;
        $campagne->save();
        return response()->json(new RESTResponse(200, "OK", null));
    }

    /**
     * Display the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(new RESTResponse(200, "OK", Campagne::find($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $campagne = Campagne::find($id);
        if($campagne != null){
            $campagne->nom = $request->input('nom');
            $campagne->type_remuneration = $request->input('type_remuneration');
            $campagne->remuneration = $request->input('remuneration');
            $campagne->annonceur()->dissociate();
            $annonceur = Annonceur::find($request->input('annonceur'));
            $campagne->annonceur()->associate($annonceur);
            $campagne->modifie_par = Auth::user()->id;
            $campagne->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaitez modifier n'existe pas dans la Base de données !", null));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $campagne = Campagne::find($id);
        if($campagne != null){
            $campagne->annonceur()->dissociate();
            $campagne->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
        return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
