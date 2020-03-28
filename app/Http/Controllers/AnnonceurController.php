<?php

namespace App\Http\Controllers;

use App\Annonceur;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\Resultat;
use App\Campagne;
use App\Routeur;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OthersResponses\AnnonceurOtherResponse;
use App\Http\Controllers\OthersResponses\AnnonceurStatsOtherResponse;

class AnnonceurController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $annonceurs = Annonceur::all();
        $annonceurs->transform(function ($item, $key) {
            $annonceur = new AnnonceurOtherResponse
                        (
                            $item->id, 
                            $item->nom,
                            $item->url,
                            $item->adresse_facturation, 
                            $item->email_comptabilite,
                            $item->email_direction,
                            $item->email_production,
                            $item->delai_paiement,
                            date('d-m-Y à H:i:s', strtotime($item->created_at)),
                            User::find($item->cree_par)->name,
                            date('d-m-Y à H:i:s', strtotime($item->updated_at)),
                            User::find($item->modifie_par)->name
                        );
            return $annonceur;
        });
        return response()->json(new RESTResponse(200, "OK", $annonceurs));
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
            $annonceur = new AnnonceurStatsOtherResponse
                        (
                            $item->id, 
                            Annonceur::find($item->annonceur_id)->nom,
                            Annonceur::find($item->annonceur_id),
                            Campagne::find($item->campagne_id)->remuneration,
                            $item->resultat,
                            Routeur::find($item->routeur_id)->prix * $item->volume,
                            date('d-m-Y à H:i:s', strtotime($item->created_at)),
                            User::find($item->cree_par)->name,
                            date('d-m-Y à H:i:s', strtotime($item->updated_at)),
                            User::find($item->modifie_par)->name
                        );
            return $annonceur;
        });
        return response()->json(new RESTResponse(200, "OK", $annonceurs));
    }

    /**
     * Apply filter to retrieve correct data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyFilter(Request $request){
        $annonceurs = Resultat::whereIn();
        $annonceurs->transform(function ($item, $key) {
            $annonceur = new AnnonceurStatsOtherResponse
                        (
                            $item->id, 
                            Annonceur::find($item->annonceur_id)->nom,
                            Annonceur::find($item->annonceur_id),
                            Campagne::find($item->campagne_id)->remuneration,
                            $item->resultat,
                            Routeur::find($item->routeur_id)->prix * $item->volume,
                            date('d-m-Y à H:i:s', strtotime($item->created_at)),
                            User::find($item->cree_par)->name,
                            date('d-m-Y à H:i:s', strtotime($item->updated_at)),
                            User::find($item->modifie_par)->name
                        );
            return $annonceur;
        });
        return response()->json(new RESTResponse(200, "OK", $annonceurs));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
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
            'delai_paiement'=>$request->input('delai_paiement'),
            'cree_par'=>Auth::user()->id,
            'modifie_par'=>Auth::user()->id
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
                    'delai_paiement'=>$request->input('delai_paiement'),
                    'modifie_par'=>Auth::user()->id
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
