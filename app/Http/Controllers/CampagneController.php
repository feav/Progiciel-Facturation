<?php

namespace App\Http\Controllers;

use App\Base;
use App\Resultat;
use App\Campagne;
use App\Routeur;
use App\Annonceur;
use App\User;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\RESTPaginateResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OthersResponses\CampagneOtherResponse;
use App\Http\Controllers\OthersResponses\CampagneStatsOtherResponse;

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
            $campagne = new CampagneOtherResponse ($item->id, $item->nom, $item->type_remuneration, $item->remuneration, Annonceur::find($item->annonceur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name);
            return $campagne;
        });
        return response()->json(new RESTResponse(200, "OK", $campagnes));
    }

    /**
     * Display a listing of the resource by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginate($per_page = 15){
        $campagnes = Campagne::paginate($per_page);
        $campagnes->transform(function ($item, $key) {
            $campagne = new CampagneOtherResponse ($item->id, $item->nom, $item->type_remuneration, $item->remuneration, Annonceur::find($item->annonceur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name);
            return $campagne;
        });
        return response()
                ->json(new RESTPaginateResponse($campagnes->currentPage(), $campagnes->items(), $campagnes->url(1), $campagnes->lastPage(), $campagnes->url($campagnes->lastPage()), $campagnes->nextPageUrl(), $campagnes->perPage(), $campagnes->previousPageUrl(), $campagnes->count(), $campagnes->total()));
	}
	
	/**
     * Display a listing of the resource using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchPaginate($per_page = 15, $search_text=""){
        $campagnes = Campagne::where('nom', 'like', '%' . $search_text . '%')->paginate($per_page);
        $campagnes->transform(function ($item, $key) {
            $campagne = new CampagneOtherResponse ($item->id, $item->nom, $item->type_remuneration, $item->remuneration, Annonceur::find($item->annonceur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name);
            return $campagne;
        });
        return response()
                ->json(new RESTPaginateResponse($campagnes->currentPage(), $campagnes->items(), $campagnes->url(1), $campagnes->lastPage(), $campagnes->url($campagnes->lastPage()), $campagnes->nextPageUrl(), $campagnes->perPage(), $campagnes->previousPageUrl(), $campagnes->count(), $campagnes->total()));
	}
	
	/**
     * Display a listing of the resource for statistics by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForStatistics(){
        //$campagnes = Resultat::paginate($per_page);
        $campagnesFull = Resultat::all();

        $totalVolume = $campagnesFull->sum("volume");
        
        $campagnesFull->transform(function ($item, $key) {
            $campagne = new CampagneStatsOtherResponse($item->id, Campagne::find($item->campagne_id)->nom, Annonceur::find($item->annonceur_id), $item->remuneration, $item->resultat, Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name);
            return $campagne;
        });
        $campagnes = $campagnesFull->uniqueStrict("nom");
        $campagnes->each(function ($item, $key) use($campagnesFull) {
            $item->resultat = $campagnesFull->where('nom', $item->nom)->sum("resultat");
            $item->pa = $campagnesFull->where('nom', $item->nom)->sum("pa");
        });
        
        $totalPA = $campagnes->sum("pa");
        $totalCA = $campagnes->sum(function ($item) { return $item->rem * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $response = array(  'totalVolume'=>$totalVolume, 
                            'totalPA'=>$totalPA,
                            'totalCA'=>$totalCA,
                            'totalMarge'=>$totalMarge,
                            'data'=>new RESTResponse(200, "OK", $campagnes));
        return response()->json($response);
    }
	
	/**
     * Display a listing of the resource for statistics using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchForStatistics($search_text="", Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $campagnesFull = Resultat::whereHas('campagne', function ($query) use($search_text) {
            $query->where('nom', 'like', '%' . $search_text . '%');
        })->whereBetween('date_envoi', [$from, $to])->get();

        $totalVolume = $campagnesFull->sum("volume");
        
        $campagnesFull->transform(function ($item, $key) {
            $campagne = new CampagneStatsOtherResponse($item->id, Campagne::find($item->campagne_id)->nom, Annonceur::find($item->annonceur_id), $item->remuneration, $item->resultat, Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name);
            return $campagne;
        });
        $campagnes = $campagnesFull->uniqueStrict("nom");
        $campagnes->each(function ($item, $key) use($campagnesFull) {
            $item->resultat = $campagnesFull->where('nom', $item->nom)->sum("resultat");
            $item->pa = $campagnesFull->where('nom', $item->nom)->sum("pa");
        });
        
        $totalPA = $campagnes->sum("pa");
        $totalCA = $campagnes->sum(function ($item) { return $item->rem * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $response = array(  'totalVolume'=>$totalVolume, 
                            'totalPA'=>$totalPA,
                            'totalCA'=>$totalCA,
                            'totalMarge'=>$totalMarge,
                            'data'=>new RESTResponse(200, "OK", $campagnes));
        return response()->json($response);
    }

    /**
     * Apply filter to retrieve correct data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyFilterForStatistics(Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));
        $campagnesFull = Resultat::whereBetween('date_envoi', [$from, $to])->get();

        $totalVolume = $campagnesFull->sum("volume");
        
        $campagnesFull->transform(function ($item, $key) {
            $campagne = new CampagneStatsOtherResponse($item->id, Campagne::find($item->campagne_id)->nom, Annonceur::find($item->annonceur_id), $item->remuneration, $item->resultat, Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name);
            return $campagne;
        });
        $campagnes = $campagnesFull->uniqueStrict("nom");
        $campagnes->each(function ($item, $key) use($campagnesFull) {
            $item->resultat = $campagnesFull->where('nom', $item->nom)->sum("resultat");
            $item->pa = $campagnesFull->where('nom', $item->nom)->sum("pa");
        });
        
        $totalPA = $campagnes->sum("pa");
        $totalCA = $campagnes->sum(function ($item) { return $item->rem * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $response = array(  'totalVolume'=>$totalVolume, 
                            'totalPA'=>$totalPA,
                            'totalCA'=>$totalCA,
                            'totalMarge'=>$totalMarge,
                            'data'=>new RESTResponse(200, "OK", $campagnes));
        return response()->json($response);
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
    public function updateCampagne(Request $request, $id)
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
    public function deleteCampagne($id)
    {
        $campagne = Campagne::find($id);
        if($campagne != null){
            $campagne->annonceur()->dissociate();
            $campagne->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
        return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
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
