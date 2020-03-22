<?php

namespace App\Http\Controllers;

use App\Annonceur;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\Resultat;
use App\Campagne;
use App\Routeur;
use App\Http\Controllers\OthersResponses\AnnonceurOtherResponse;

class AnnonceurController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(new RESTResponse(200, "OK", Annonceur::all()));
    }

    /**
     * Display a listing of the resource for statistics.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForStatistics()
    {
        $annonceurs = Resultat::all();
        $annonceurs->transform(function ($item, $key) {
            $annonceur = new AnnonceurOtherResponse
                        (
                            $item->id, 
                            Annonceur::find($item->annonceur_id)->nom,
                            Campagne::find($item->campagne_id)->remuneration,
                            $item->resultat,
                            Routeur::find($item->routeur_id)->prix * $item->volume
                        );
            return $annonceur;
        });
        return response()->json(new RESTResponse(200, "OK", $annonceurs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Annonceur::create([
            'nom'=>$request->input('nom'),
            'url'=>$request->input('url'),
            'adresse_facturation'=>$request->input('adresse_facturation'),
            'email_comptabilite'=>$request->input('email_comptabilite'), 
            'email_direction'=>$request->input('email_direction'),
            'email_production'=>$request->input('email_production'), 
            'delai_paiement'=>$request->input('delai_paiement')
        ]);
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
        return response()->json(new RESTResponse(200, "OK", Annonceur::find($id)));
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
        Annonceur::where('id',$id)
                ->update([
                    'nom'=>$request->input('nom'),
                    'url'=>$request->input('url'), 
                    'adresse_facturation'=>$request->input('adresse_facturation'),
                    'email_comptabilite'=>$request->input('email_comptabilite'), 
                    'email_direction'=>$request->input('email_direction'),
                    'email_production'=>$request->input('email_production'), 
                    'delai_paiement'=>$request->input('delai_paiement')
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
        $annonceur = Annonceur::find($id);
        if($annonceur != null){
            $annonceur->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
