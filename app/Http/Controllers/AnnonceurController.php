<?php

namespace App\Http\Controllers;

use App\Annonceur;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\RESTPaginateResponse;
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
            $annonceur = new AnnonceurOtherResponse ($item->id, $item->nom, $item->url, $item->login, $item->password, $item->adresse_facturation, $item->email_comptabilite, $item->email_direction, $item->email_production, $item->delai_paiement, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $annonceur;
        });
        return response()->json(new RESTResponse(200, "OK", $annonceurs));
    }

    /**
     * Display a listing of the resource by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginate($per_page = 15){
        $annonceurs = Annonceur::paginate($per_page);
        $annonceurs->transform(function ($item, $key) {
            $annonceur = new AnnonceurOtherResponse ($item->id, $item->nom, $item->url, $item->login, $item->password, $item->adresse_facturation, $item->email_comptabilite, $item->email_direction, $item->email_production, $item->delai_paiement, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $annonceur;
        });
        return response()
                ->json(new RESTPaginateResponse($annonceurs->currentPage(), $annonceurs->items(), $annonceurs->url(1), $annonceurs->lastPage(), $annonceurs->url($annonceurs->lastPage()), $annonceurs->nextPageUrl(), $annonceurs->perPage(), $annonceurs->previousPageUrl(), $annonceurs->count(), $annonceurs->total()));
	}
	
	/**
     * Display a listing of the resource using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchPaginate($per_page = 15, $search_text=""){
        $annonceurs = Annonceur::where('nom', 'like', '%' . $search_text . '%')->paginate($per_page);
        $annonceurs->transform(function ($item, $key) {
            $annonceur = new AnnonceurOtherResponse ($item->id, $item->nom, $item->url, $item->login, $item->password, $item->adresse_facturation, $item->email_comptabilite, $item->email_direction, $item->email_production, $item->delai_paiement, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $annonceur;
        });
        return response()
                ->json(new RESTPaginateResponse($annonceurs->currentPage(), $annonceurs->items(), $annonceurs->url(1), $annonceurs->lastPage(), $annonceurs->url($annonceurs->lastPage()), $annonceurs->nextPageUrl(), $annonceurs->perPage(), $annonceurs->previousPageUrl(), $annonceurs->count(), $annonceurs->total()));
	}
	
	/**
     * Display a listing of the resource for statistics by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForStatistics($page = 1, $per_page = 15){
        $resultatsFull = Resultat::all();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->id, 
                Annonceur::find($item->annonceur_id)->nom, 
                Annonceur::find($item->annonceur_id), 
                Resultat::where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $annonceur;
        });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $totalCAPartiel - $totalPAPartiel;
        
        $response = array(  
            'total'=>$total,
            'totalVolume'=>$totalVolume, 
            'totalPA'=>$totalPA,
            'totalCA'=>$totalCA,
            'totalMarge'=>$totalMarge,
            'totalVolumePartiel'=>$totalVolumePartiel, 
            'totalPAPartiel'=>$totalPAPartiel,
            'totalCAPartiel'=>$totalCAPartiel,
            'totalMargePartiel'=>$totalMargePartiel,
            'data'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
	}
	
	/**
     * Display a listing of the resource for statistics using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchPaginateForStatistics($per_page = 15, $search_text=""){
        $annonceurs = Resultat::whereHas('campagne', function ($query) use($search_text) {
            $query->whereHas('annonceur', function($query) use($search_text){
                $query->where('nom', 'like', '%' . $search_text . '%');
            });
        })->paginate($per_page);
        $annonceurs->transform(function ($item, $key) {
            $annonceur = new AnnonceurStatsOtherResponse ($item->id, Annonceur::find($item->annonceur_id)->nom, Annonceur::find($item->annonceur_id), Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $annonceur;
        });
        return response()
                ->json(new RESTPaginateResponse($annonceurs->currentPage(), $annonceurs->items(), $annonceurs->url(1), $annonceurs->lastPage(), $annonceurs->url($annonceurs->lastPage()), $annonceurs->nextPageUrl(), $annonceurs->perPage(), $annonceurs->previousPageUrl(), $annonceurs->count(), $annonceurs->total()));
	}

    /**
     * Apply filter to retrieve correct data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyFilterForStatistics($page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])->get();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->id, 
                Annonceur::find($item->annonceur_id)->nom, 
                Annonceur::find($item->annonceur_id), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::whereBetween('date_envoi', [$from, $to])->where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $annonceur;
        });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $totalCAPartiel - $totalPAPartiel;

        $response = array(  
            'total'=>$total,
            'totalVolume'=>$totalVolume, 
            'totalPA'=>$totalPA,
            'totalCA'=>$totalCA,
            'totalMarge'=>$totalMarge,
            'totalVolumePartiel'=>$totalVolumePartiel, 
            'totalPAPartiel'=>$totalPAPartiel,
            'totalCAPartiel'=>$totalCAPartiel,
            'totalMargePartiel'=>$totalMargePartiel,
            'data'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
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
            'login'=>$request->input('login'),
            'password'=>$request->input('password'),
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
    public function updateAnnonceur(Request $request, $id)
    {
        Annonceur::where('id',$id)
                ->update([
                    'nom'=>$request->input('nom'),
                    'url'=>$request->input('url'), 
                    'login'=>$request->input('login'),
                    'password'=>$request->input('password'),
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
                    'login'=>$request->input('login'),
                    'password'=>$request->input('password'),
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
    public function deleteAnnonceur($id)
    {
        $annonceur = Annonceur::find($id);
        if($annonceur != null){
            $annonceur->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
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
