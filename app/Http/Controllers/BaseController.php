<?php

namespace App\Http\Controllers;

use App\Base;
use App\Resultat;
use App\Campagne;
use App\Routeur;
use App\Annonceur;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\RESTPaginateResponse;
use App\Http\Controllers\OthersResponses\BaseOtherResponse;
use App\Http\Controllers\OthersResponses\BaseStatsOtherResponse;
use App\Http\Controllers\OthersResponses\AnnonceurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\RouteurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\CampagneStatsOtherResponse;

class BaseController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bases = Base::all();
        $bases->transform(function ($item, $key) {
            $base = new BaseOtherResponse($item->id, $item->nom, Routeur::find($item->routeur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $base;
        });
        return response()->json(new RESTResponse(200, "OK", $bases));
    }

    /**
     * Display a listing of the resource by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginate($per_page = 15){
        $bases = Base::paginate($per_page);
        $bases->transform(function ($item, $key) {
            $base = new BaseOtherResponse($item->id, $item->nom, Routeur::find($item->routeur_id)->nom, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $base;
        });
        return response()
                ->json(new RESTPaginateResponse($bases->currentPage(), $bases->items(), $bases->url(1), $bases->lastPage(), $bases->url($bases->lastPage()), $bases->nextPageUrl(), $bases->perPage(), $bases->previousPageUrl(), $bases->count(), $bases->total()));
	}
	
	/**
     * Display a listing of the resource using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchPaginate($per_page = 15, $search_text=""){
        $bases = Base::where('nom', 'like', '%' . $search_text . '%')->paginate($per_page);
        $bases->transform(function ($item, $key) {
            $base = new BaseOtherResponse($item->id, $item->nom, Routeur::find($item->routeur_id), date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $base;
        });
        return response()
                ->json(new RESTPaginateResponse($bases->currentPage(), $bases->items(), $bases->url(1), $bases->lastPage(), $bases->url($bases->lastPage()), $bases->nextPageUrl(), $bases->perPage(), $bases->previousPageUrl(), $bases->count(), $bases->total()));
	}
	
	/**
     * Display a listing of the resource for statistics by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForStatistics($page = 1, $per_page = 15){
        $resultatsFull = Resultat::all();

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) {
            $base = new BaseStatsOtherResponse(
                $item->base_id, 
                Base::find($item->base_id)->nom,
                Resultat::where('base_id', $item->base_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('base_id', $item->base_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('base_id', $item->base_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $base;
        });
        $resultats->each(function ($item, $key) { $item->pm = $item->ca - $item->pa; });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $resultats->sum("pm");
        
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
	
    public function indexForStatisticsForAnnonceurs($base_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('base_id', $base_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($base_id) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->annonceur_id, 
                Annonceur::find($item->annonceur_id)->nom,
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $annonceur;
        });
        $resultats->each(function ($item, $key) { $item->pm = $item->ca - $item->pa; });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $resultats->sum("pm");

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

    public function indexForStatisticsForCampagnes($base_id, $annonceur_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('base_id', $base_id)
                                    ->where('annonceur_id', $annonceur_id)->get();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($base_id, $annonceur_id) {
            $campagne = new CampagneStatsOtherResponse(
                $item->campagne_id, 
                Campagne::find($item->campagne_id)->nom,
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $campagne;
        });
        $resultats->each(function ($item, $key) { $item->pm = $item->ca - $item->pa; });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $resultats->sum("pm");

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

    public function indexForStatisticsForRouteurs($base_id, $annonceur_id, $campagne_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('base_id', $base_id)
                                    ->where('annonceur_id', $annonceur_id)
                                    ->where('campagne_id', $campagne_id)
                                    ->get();
        
        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($base_id, $annonceur_id, $campagne_id) {
            $routeur = new RouteurStatsOtherResponse(
                $item->routeur_id, 
                Routeur::find($item->routeur_id)->nom,
                Routeur::find($item->routeur_id)->prix,
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $routeur;
        });
        $resultats->each(function ($item, $key) { $item->pm = $item->ca - $item->pa; });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $resultats->sum("pm");

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
    public function indexSearchPaginateForStatistics($per_page = 15, $search_text=""){
        $bases = Resultat::whereHas('base', function ($query) use($search_text) {
            $query->where('nom', 'like', '%' . $search_text . '%');
        })->paginate($per_page);
        $bases->transform(function ($item, $key) {
            $base = new BaseStatsOtherResponse($item->id, Base::find($item->base_id)->nom, Annonceur::find($item->annonceur_id), Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $base;
        });
        return response()
                ->json(new RESTPaginateResponse($bases->currentPage(), $bases->items(), $bases->url(1), $bases->lastPage(), $bases->url($bases->lastPage()), $bases->nextPageUrl(), $bases->perPage(), $bases->previousPageUrl(), $bases->count(), $bases->total()));
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

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to) {
            $base = new BaseStatsOtherResponse(
                $item->base_id, 
                Base::find($item->base_id)->nom,
                Resultat::whereBetween('date_envoi', [$from, $to])->where('base_id', $item->base_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('base_id', $item->base_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])->where('base_id', $item->base_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $base;
        });
        $resultats->each(function ($item, $key) { $item->pm = $item->ca - $item->pa; });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $resultats->sum("pm");

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

    public function applyFilterForStatisticsForAnnonceurs($base_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('base_id', $base_id)->get();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $base_id) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->annonceur_id, 
                Annonceur::find($item->annonceur_id)->nom,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $annonceur;
        });
        $resultats->each(function ($item, $key) { $item->pm = $item->ca - $item->pa; });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $resultats->sum("pm");

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

    public function applyFilterForStatisticsForCampagnes($base_id, $annonceur_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('base_id', $base_id)
                                    ->where('annonceur_id', $annonceur_id)->get();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $base_id, $annonceur_id) {
            $campagne = new CampagneStatsOtherResponse(
                $item->campagne_id, 
                Campagne::find($item->campagne_id)->nom,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()->sum("volume"), 
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
        $totalMargePartiel = $resultats->sum("pm");

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

    public function applyFilterForStatisticsForRouteurs($base_id, $annonceur_id, $campagne_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('base_id', $base_id)
                                    ->where('annonceur_id', $annonceur_id)
                                    ->where('campagne_id', $campagne_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $base_id, $annonceur_id, $campagne_id) {
            $routeur = new RouteurStatsOtherResponse(
                $item->routeur_id, 
                Routeur::find($item->routeur_id)->nom,
                Routeur::find($item->routeur_id)->prix,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            return $routeur;
        });
        $resultats->each(function ($item, $key) { $item->pm = $item->ca - $item->pa; });
        
        $totalVolumePartiel = $resultats->sum("volume");
        $totalPAPartiel = $resultats->sum("pa");
        $totalCAPartiel = $resultats->sum("ca");
        $totalMargePartiel = $resultats->sum("pm");

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
        $base->cree_par = Auth::user()->id;
        $base->modifie_par = Auth::user()->id;
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
    public function updateBase(Request $request, $id)
    {
        $base = Base::find($id);
        if($base != null){
            $base->nom = $request->input('nom');
            $routeur = Routeur::find($request->input('routeur'));
            $base->routeur()->dissociate();
            $base->routeur()->associate($routeur);
            $base->modifie_par = Auth::user()->id;
            $base->save();
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
        $base = Base::find($id);
        if($base != null){
            $base->nom = $request->input('nom');
            $routeur = Routeur::find($request->input('routeur'));
            $base->routeur()->dissociate();
            $base->routeur()->associate($routeur);
            $base->modifie_par = Auth::user()->id;
            $base->save();
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
    public function deleteBase($id)
    {
        $base = Base::find($id);
        if($base != null){
            $base->routeur()->dissociate();
            $base->delete();
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
        $base = Base::find($id);
        if($base != null){
            $base->routeur()->dissociate();
            $base->delete();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
        return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }
}
