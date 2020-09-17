<?php

namespace App\Http\Controllers;

use App\Planning;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\RESTPaginateResponse;
use App\Routeur;
use App\Annonceur;
use App\Base;
use App\Campagne;
use App\Resultat;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OthersResponses\PlanningResponse;

class PlanningController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plannings = Planning::all();
        $plannings->transform(function ($item, $key) {
            $planning = new PlanningResponse($item->id, date('d-m-Y', strtotime($item->date_envoi)), $item->heure_envoi, Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id), Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id)->nom, Base::find($item->base_id) == null ? null : Base::find($item->base_id), Base::find($item->base_id) == null ? null : Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id), Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id)->nom, Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id), Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id)->nom, $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $planning;
        });
        return response()->json(new RESTResponse(200, "OK", $plannings));
    }

    /**
     * Display a listing of the resource by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginate($per_page = 15){
        $plannings = Planning::paginate($per_page);
        $plannings->transform(function ($item, $key) {
            $planning = new PlanningResponse($item->id, date('d-m-Y', strtotime($item->date_envoi)), $item->heure_envoi, Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id), Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id)->nom, Base::find($item->base_id) == null ? null : Base::find($item->base_id), Base::find($item->base_id) == null ? null : Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id), Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id)->nom, Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id), Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id)->nom, $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $planning;
        });
        return response()->json(new RESTPaginateResponse($plannings->currentPage(), $plannings->items(), $plannings->url(1), $plannings->lastPage(), $plannings->url($plannings->lastPage()), $plannings->nextPageUrl(), $plannings->perPage(), $plannings->previousPageUrl(), $plannings->count(), $plannings->total()));
    }

    /**
     * Display a listing of the resource using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchPaginate($per_page = 15, $search_text=""){
        $plannings = Planning::whereHas('campagne', function ($query) use($search_text) {
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
        $plannings->transform(function ($item, $key) {
            $planning = new PlanningResponse($item->id, date('d-m-Y', strtotime($item->date_envoi)), $item->heure_envoi, Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id), Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id)->nom, Base::find($item->base_id) == null ? null : Base::find($item->base_id), Base::find($item->base_id) == null ? null : Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id), Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id)->nom, Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id), Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id)->nom, $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $planning;
        });
        return response()->json(new RESTPaginateResponse($plannings->currentPage(), $plannings->items(), $plannings->url(1), $plannings->lastPage(), $plannings->url($plannings->lastPage()), $plannings->nextPageUrl(), $plannings->perPage(), $plannings->previousPageUrl(), $plannings->count(), $plannings->total()));
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
        $plannings = Planning::whereBetween('date_envoi', [$from, $to])->orderBy('date_envoi')->paginate($per_page);
        $plannings->transform(function ($item, $key) {
            $planning = new PlanningResponse($item->id, date('d-m-Y', strtotime($item->date_envoi)), $item->heure_envoi, Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id), Routeur::find($item->routeur_id) == null ? null : Routeur::find($item->routeur_id)->nom, Base::find($item->base_id) == null ? null : Base::find($item->base_id), Base::find($item->base_id) == null ? null : Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id), Annonceur::find($item->annonceur_id) == null ? null : Annonceur::find($item->annonceur_id)->nom, Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id), Campagne::find($item->campagne_id) == null ? null : Campagne::find($item->campagne_id)->nom, $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $planning;
        });
        return response()->json(new RESTPaginateResponse($plannings->currentPage(), $plannings->items(), $plannings->url(1), $plannings->lastPage(), $plannings->url($plannings->lastPage()), $plannings->nextPageUrl(), $plannings->perPage(), $plannings->previousPageUrl(), $plannings->count(), $plannings->total()));
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
        $planning->heure_envoi = $request->input('heure_envoi');
        $resultat->volume = $request->input('volume');
        $resultat->date_envoi = $request->input('date_envoi');
        $resultat->heure_envoi = $request->input('heure_envoi');

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

        $planning->remuneration = $campagne->remuneration;
        $resultat->remuneration = $campagne->remuneration;

        $planning->cree_par = Auth::user()->id;
        $resultat->cree_par = Auth::user()->id;
        $planning->modifie_par = Auth::user()->id;
        $resultat->modifie_par = Auth::user()->id;
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
    public function updatePlanning(Request $request, $id)
    {
        $planning = Planning::find($id);
        $resultat = Resultat::find($id);
        if($planning != null && $resultat != null){
            $planning->volume = $request->input('volume');
            $planning->date_envoi = $request->input('date_envoi');
            $planning->heure_envoi = $request->input('heure_envoi');

            $planning->base()->dissociate();
            $planning->campagne()->dissociate();

            $base = Base::find($request->input('base'));
            $campagne = Campagne::find($request->input('campagne'));

            $planning->base()->associate($base);
            $planning->campagne()->associate($campagne);

            $planning->routeur_id = $base->routeur_id;
            $planning->annonceur_id = $campagne->annonceur_id;
            $planning->modifie_par = Auth::user()->id;
            $planning->remuneration = $campagne->remuneration;
        
            $resultat->volume = $request->input('volume');
            $resultat->date_envoi = $request->input('date_envoi');
            $resultat->heure_envoi = $request->input('heure_envoi');

            $resultat->base()->dissociate();
            $resultat->campagne()->dissociate();

            $resultat->base()->associate($base);
            $resultat->campagne()->associate($campagne);

            $resultat->routeur_id = $base->routeur_id;
            $resultat->annonceur_id = $campagne->annonceur_id;
            $resultat->modifie_par = Auth::user()->id;
            $resultat->remuneration = $campagne->remuneration;

            $planning->save();
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
    public function deletePlanning($id)
    {
        $planning = Planning::find($id);
        $resultat = Resultat::find($id);
        if($planning != null && $resultat != null){
            $planning->base()->dissociate();
            $planning->campagne()->dissociate();
            $resultat->base()->dissociate();
            $resultat->campagne()->dissociate();
            $planning->delete();
            $resultat->delete();
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
        $planning = Planning::find($id);
        $resultat = Resultat::find($id);
        if($planning != null && $resultat != null){
            $planning->base()->dissociate();
            $planning->campagne()->dissociate();
            $resultat->base()->dissociate();
            $resultat->campagne()->dissociate();
            $planning->delete();
            $resultat->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
        return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
