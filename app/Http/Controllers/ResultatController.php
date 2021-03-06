<?php

namespace App\Http\Controllers;

use App\Resultat;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\RESTResponse;
use App\RESTPaginateResponse;
use App\Base;
use App\Campagne;
use App\Routeur;
use App\Annonceur;
use App\User;
use App\Http\Controllers\OthersResponses\ResultatResponse;
use Illuminate\Support\Facades\Auth;

class ResultatController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    /**
     * Display a listing of the resource by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginate($per_page = 15)
    {
        $resultats = Resultat::where('shown', 1)->paginate($per_page);
        $resultats->transform(function ($item, $key) {
            $resultat = new ResultatResponse($item->id, date('d-m-Y', strtotime($item->date_envoi)), $item->heure_envoi, Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id), Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id)->nom, Base::find($item->base_id) == null ? null : Base::find($item->base_id), Base::find($item->base_id) == null ? null : Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id), Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id)->nom, Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id), Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id)->nom, $item->volume, $item->resultat, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted, $item->shown);
            return $resultat;
        });
        return response()
                ->json(new RESTPaginateResponse($resultats->currentPage(), $resultats->items(), $resultats->url(1), $resultats->lastPage(), $resultats->url($resultats->lastPage()), $resultats->nextPageUrl(), $resultats->perPage(), $resultats->previousPageUrl(), $resultats->count(), $resultats->total()));
    }

    /**
     * Display a listing of the resource using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchPaginate($per_page = 15, $search_text=""){
        $resultats = Resultat::where('shown', 1)->whereHas('campagne', function ($query) use($search_text) {
            $query->where('nom', 'like', '%' . $search_text . '%');
        })->orWhereHas('base', function ($query) use($search_text) {
            $query->where('nom', 'like', '%' . $search_text . '%');
        })->orWhereHas('base', function ($query) use($search_text) {
            $query->whereHas('routeur', function($query) use($search_text){
                $query->where('nom', 'like', '%' . $search_text . '%');
            });
        })->orWhereHas('campagne', function ($query) use($search_text) {
            $query->whereHas('annonceur', function($query) use($search_text){
                $query->where('nom', 'like', '%' . $search_text . '%');
            });
        })->paginate($per_page);
        $resultats->transform(function ($item, $key) {
            $resultat = new ResultatResponse($item->id, date('d-m-Y', strtotime($item->date_envoi)), $item->heure_envoi, Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id), Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id)->nom, Base::find($item->base_id) == null ? null : Base::find($item->base_id), Base::find($item->base_id) == null ? null : Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id), Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id)->nom, Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id), Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id)->nom, $item->volume, $item->resultat, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted, $item->shown);
            return $resultat;
        });
        return response()
                ->json(new RESTPaginateResponse($resultats->currentPage(), $resultats->items(), $resultats->url(1), $resultats->lastPage(), $resultats->url($resultats->lastPage()), $resultats->nextPageUrl(), $resultats->perPage(), $resultats->previousPageUrl(), $resultats->count(), $resultats->total()));
	}

    /**
     * Apply filter to retrieve correct data.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function applyFilter($per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));
        
        $resultats;

        if($request->filtre_annonceur==null && $request->filtre_routeur==null && $request->filtre_campagne==null && $request->filtre_base==null){
            $resultats = Resultat::whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur!=null && $request->filtre_routeur==null && $request->filtre_campagne==null && $request->filtre_base==null){
            $resultats = Resultat::where([ 'annonceur_id' => $request->filtre_annonceur ])
                                    ->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur==null && $request->filtre_routeur!=null && $request->filtre_campagne==null && $request->filtre_base==null){
            $resultats = Resultat::where([ 'routeur_id' => $request->filtre_routeur ])
                                    ->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur==null && $request->filtre_routeur==null && $request->filtre_campagne!=null && $request->filtre_base==null){
            $resultats = Resultat::where([ 'campagne_id' => $request->filtre_campagne ])
                                    ->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur==null && $request->filtre_routeur==null && $request->filtre_campagne==null && $request->filtre_base!=null){
            $resultats = Resultat::where([ 'base_id' => $request->filtre_base ])
                                    ->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur==null && $request->filtre_routeur!=null && $request->filtre_campagne!=null && $request->filtre_base!=null){
            $resultats = Resultat::where([
                'routeur_id' => $request->filtre_routeur,
                'campagne_id' => $request->filtre_campagne,
                'base_id' => $request->filtre_base
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur!=null && $request->filtre_routeur==null && $request->filtre_campagne!=null && $request->filtre_base!=null){
            $resultats = Resultat::where([
                'annonceur_id' => $request->filtre_annonceur,
                'campagne_id' => $request->filtre_campagne,
                'base_id' => $request->filtre_base
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur!=null && $request->filtre_routeur!=null && $request->filtre_campagne==null && $request->filtre_base!=null){
            $resultats = Resultat::where([
                'annonceur_id' => $request->filtre_annonceur,
                'routeur_id' => $request->filtre_routeur,
                'base_id' => $request->filtre_base
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur!=null && $request->filtre_routeur!=null && $request->filtre_campagne!=null && $request->filtre_base==null){
            $resultats = Resultat::where([
                'annonceur_id' => $request->filtre_annonceur,
                'routeur_id' => $request->filtre_routeur,
                'campagne_id' => $request->filtre_campagne
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur==null && $request->filtre_routeur==null && $request->filtre_campagne!=null && $request->filtre_base!=null){
            $resultats = Resultat::where([
                'campagne_id' => $request->filtre_campagne,
                'base_id' => $request->filtre_base
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur==null && $request->filtre_routeur!=null && $request->filtre_campagne==null && $request->filtre_base!=null){
            $resultats = Resultat::where([
                'routeur_id' => $request->filtre_routeur,
                'base_id' => $request->filtre_base
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur==null && $request->filtre_routeur!=null && $request->filtre_campagne!=null && $request->filtre_base==null){
            $resultats = Resultat::where([
                'routeur_id' => $request->filtre_routeur,
                'campagne_id' => $request->filtre_campagne
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur!=null && $request->filtre_routeur==null && $request->filtre_campagne==null && $request->filtre_base!=null){
            $resultats = Resultat::where([
                'annonceur_id' => $request->filtre_annonceur,
                'base_id' => $request->filtre_base
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur!=null && $request->filtre_routeur==null && $request->filtre_campagne!=null && $request->filtre_base==null){
            $resultats = Resultat::where([
                'annonceur_id' => $request->filtre_annonceur,
                'campagne_id' => $request->filtre_campagne
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else if($request->filtre_annonceur!=null && $request->filtre_routeur!=null && $request->filtre_campagne==null && $request->filtre_base==null){
            $resultats = Resultat::where([
                'annonceur_id' => $request->filtre_annonceur,
                'routeur_id' => $request->filtre_routeur,
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }else {
            $resultats = Resultat::where([
                'annonceur_id' => $request->filtre_annonceur,
                'routeur_id' => $request->filtre_routeur,
                'campagne_id' => $request->filtre_campagne,
                'base_id' => $request->filtre_base
            ])->whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        }

        $resultats->transform(function ($item, $key) {
            $resultat = new ResultatResponse($item->id, date('d-m-Y', strtotime($item->date_envoi)), $item->heure_envoi, Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id), Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id)->nom, Base::find($item->base_id) == null ? null : Base::find($item->base_id), Base::find($item->base_id) == null ? null : Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id), Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id)->nom, Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id), Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id)->nom, $item->volume, $item->resultat, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted, $item->shown);
            return $resultat;
        });
        return response()
                ->json(new RESTPaginateResponse($resultats->currentPage(), $resultats->items(), $resultats->url(1), $resultats->lastPage(), $resultats->url($resultats->lastPage()), $resultats->nextPageUrl(), $resultats->perPage(), $resultats->previousPageUrl(), $resultats->count(), $resultats->total()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $resultat = new Resultat;
        $resultat->volume = $request->input('volume');
        $resultat->date_envoi = $request->input('date_envoi');
        $resultat->heure_envoi = $request->input('heure_envoi');
        $resultat->resultat = $request->input('resultat');
        $base = Base::find($request->input('base'));
        $campagne = Campagne::find($request->input('campagne'));
        $resultat->base()->associate($base);
        $resultat->campagne()->associate($campagne);
        $resultat->remuneration = $campagne->remuneration;
        $resultat->routeur_id = $base->routeur_id;
        $resultat->annonceur_id = $campagne->annonceur_id;
        $resultat->cree_par = Auth::user()->id;
        $resultat->modifie_par = Auth::user()->id;
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
        return response()->json(Resultat::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateResultat(Request $request, $id)
    {
        $resultat = Resultat::find($id);
        if($resultat != null){
            $resultat->volume = $request->input('volume');
            $resultat->resultat = $request->input('resultat');
            $resultat->modifie_par = Auth::user()->id;
            $resultat->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaitez modifier n'existe pas dans la Base de données !", null));
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
        $resultat = Resultat::find($id);
        if($resultat != null){
            $resultat->volume = $request->input('volume');
            $resultat->resultat = $request->input('resultat');
            $resultat->modifie_par = Auth::user()->id;
            $resultat->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaitez modifier n'existe pas dans la Base de données !", null));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resultat = Resultat::find($id);
        if($resultat != null){
            // $resultat->base()->dissociate();
            // $resultat->campagne()->dissociate();
            // $resultat->delete();
            $resultat->deleted = true;
            $resultat->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
        return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
