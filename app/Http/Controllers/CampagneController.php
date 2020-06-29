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
use App\Http\Controllers\OthersResponses\AnnonceurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\RouteurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\BaseStatsOtherResponse;

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
            $campagne = new CampagneOtherResponse ($item->id, $item->nom, $item->type_remuneration, $item->remuneration, Annonceur::find($item->annonceur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
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
            $campagne = new CampagneOtherResponse ($item->id, $item->nom, $item->type_remuneration, $item->remuneration, Annonceur::find($item->annonceur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
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
            $campagne = new CampagneOtherResponse ($item->id, $item->nom, $item->type_remuneration, $item->remuneration, Annonceur::find($item->annonceur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
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
    public function indexForStatistics($page = 1, $per_page = 15){
        $resultatsFull = Resultat::all();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) {
            $campagne = new CampagneStatsOtherResponse(
                $item->id, 
                Campagne::find($item->campagne_id), 
                Annonceur::find($item->annonceur_id), 
                Resultat::where('campagne_id', $item->campagne_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('campagne_id', $item->campagne_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::where('campagne_id', $item->campagne_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $campagne;
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
    }

    public function indexForStatisticsForRouteurs($campagne_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('campagne_id', $campagne_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($campagne_id) {
            $routeur = new RouteurStatsOtherResponse(
                $item->id, 
                Routeur::find($item->routeur_id), 
                Annonceur::find($item->annonceur_id), 
                Routeur::find($item->routeur_id)->prix,
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $routeur;
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
    }
    
    public function indexForStatisticsForBases($campagne_id, $routeur_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('campagne_id', $campagne_id)
                                    ->where('routeur_id', $routeur_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($campagne_id, $routeur_id) {
            $base = new BaseStatsOtherResponse(
                $item->id, 
                Base::find($item->base_id), 
                Annonceur::find($item->annonceur_id), 
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $base;
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
    }
    
    public function indexForStatisticsForAnnonceurs($campagne_id, $routeur_id, $base_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('campagne_id', $campagne_id)
                                    ->where('routeur_id', $routeur_id)
                                    ->where('base_id', $base_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($campagne_id, $routeur_id, $base_id) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->id, 
                Annonceur::find($item->annonceur_id), 
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
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
            $campagne = new CampagneStatsOtherResponse($item->id, Campagne::find($item->campagne_id)->nom, Annonceur::find($item->annonceur_id), Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
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
    public function applyFilterForStatistics($page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])->get();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to) {
            $campagne = new CampagneStatsOtherResponse(
                $item->id, 
                Campagne::find($item->campagne_id), 
                Annonceur::find($item->annonceur_id), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('campagne_id', $item->campagne_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('campagne_id', $item->campagne_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::whereBetween('date_envoi', [$from, $to])->where('campagne_id', $item->campagne_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $campagne;
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
    }

    public function applyFilterForStatisticsForRouteurs($campagne_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('campagne_id', $campagne_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $campagne_id) {
            $routeur = new RouteurStatsOtherResponse(
                $item->id, 
                Routeur::find($item->routeur_id), 
                Annonceur::find($item->annonceur_id), 
                Routeur::find($item->routeur_id)->prix,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $routeur;
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
    }

    public function applyFilterForStatisticsForBases($campagne_id, $routeur_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('campagne_id', $campagne_id)
                                    ->where('routeur_id', $routeur_id)->get();

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $campagne_id, $routeur_id) {
            $base = new BaseStatsOtherResponse(
                $item->id, 
                Base::find($item->base_id), 
                Annonceur::find($item->annonceur_id), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $base;
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
        return response()->json($response);
    }

    public function applyFilterForStatisticsForAnnonceurs($campagne_id, $routeur_id, $base_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('campagne_id', $campagne_id)
                                    ->where('routeur_id', $routeur_id)
                                    ->where('base_id', $base_id)->get();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $campagne_id, $routeur_id, $base_id) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->id, 
                Annonceur::find($item->annonceur_id), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
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
            'response'=>new RESTResponse(200, "OK", $resultats)
        );
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
