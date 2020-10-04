<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Routeur;
use App\Annonceur;
use App\Campagne;
use App\Base;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\RESTPaginateResponse;
use App\Resultat;
use App\User;
use App\Http\Controllers\OthersResponses\RouteurOtherResponse;
use App\Http\Controllers\OthersResponses\RouteurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\AnnonceurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\BaseStatsOtherResponse;
use App\Http\Controllers\OthersResponses\CampagneStatsOtherResponse;

class RouteurController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $routeurs = Routeur::all();
        $routeurs->transform(function ($item, $key) {
            $routeur = new RouteurOtherResponse ($item->id, $item->nom, $item->prix, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted);
            return $routeur;
        });
        return response()->json(new RESTResponse(200, "OK", $routeurs));
    }


    /**
     * Display a listing of the resource by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPaginate($per_page = 15)
    {
        $routeurs = Routeur::paginate($per_page);
        $routeurs->transform(function ($item, $key) {
            $routeur = new RouteurOtherResponse ($item->id, $item->nom, $item->prix, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted);
            return $routeur;
        });
        return response()
                ->json(new RESTPaginateResponse($routeurs->currentPage(), $routeurs->items(), $routeurs->url(1), $routeurs->lastPage(), $routeurs->url($routeurs->lastPage()), $routeurs->nextPageUrl(), $routeurs->perPage(), $routeurs->previousPageUrl(), $routeurs->count(), $routeurs->total()));
    }

    /**
     * Display a listing of the resource using search_text by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSearchPaginate($per_page = 15, $search_text="")
    {
        $routeurs = Routeur::where('nom', 'like', '%' . $search_text . '%')
                                ->paginate($per_page);
        $routeurs->transform(function ($item, $key) {
            $routeur = new RouteurOtherResponse ($item->id, $item->nom, $item->prix, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted);
            return $routeur;
        });
        return response()
                ->json(new RESTPaginateResponse($routeurs->currentPage(), $routeurs->items(), $routeurs->url(1), $routeurs->lastPage(), $routeurs->url($routeurs->lastPage()), $routeurs->nextPageUrl(), $routeurs->perPage(), $routeurs->previousPageUrl(), $routeurs->count(), $routeurs->total() ));
    }

    /**
     * Display a listing of the resource for statistics by page.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexForStatistics($page = 1, $per_page = 15)
    {
        $resultatsFull = Resultat::all();

        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) {
            $routeur = new RouteurStatsOtherResponse(
                $item->routeur_id, 
                Routeur::find($item->routeur_id)->nom,
                Routeur::find($item->routeur_id)->prix,
                Resultat::where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::where('routeur_id', $item->routeur_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('routeur_id', $item->routeur_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
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

    public function indexForStatisticsForBases($routeur_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('routeur_id', $routeur_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($routeur_id) {
            $base = new BaseStatsOtherResponse(
                $item->base_id, 
                Base::find($item->base_id)->nom,
                Resultat::where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                Resultat::where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()->sum("volume"), 
                0,
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
    
    public function indexForStatisticsForAnnonceurs($routeur_id, $base_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('routeur_id', $routeur_id)
                                    ->where('base_id', $base_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($routeur_id, $base_id) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->annonceur_id, 
                Annonceur::find($item->annonceur_id)->nom,
                Resultat::where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('routeur_id', $routeur_id)
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

    public function indexForStatisticsForCampagnes($routeur_id, $base_id, $annonceur_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('routeur_id', $routeur_id)
                                    ->where('base_id', $base_id)
                                    ->where('annonceur_id', $annonceur_id)->get();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($routeur_id, $base_id, $annonceur_id) {
            $campagne = new CampagneStatsOtherResponse(
                $item->campagne_id, 
                Campagne::find($item->campagne_id)->nom,
                Resultat::where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('routeur_id', $routeur_id)
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
    public function indexSearchForStatistics($search_text="")
    {
        $routeurs = Resultat::whereHas('base', function ($query) use($search_text) {
            $query->whereHas('routeur', function($query) use($search_text){
                $query->where('nom', 'like', '%' . $search_text . '%');
            });
        })->get();
        $routeurs->transform(function ($item, $key) {
            $routeur = new RouteurStatsOtherResponse ($item->id, Routeur::find($item->routeur_id)->nom, Annonceur::find($item->annonceur_id), Routeur::find($item->routeur_id)->prix, $item->volume, Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name);
            return $routeur;
        });
        return response()->json(new RESTResponse(200, "OK", $routeurs));
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

        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to) {
            $routeur = new RouteurStatsOtherResponse(
                $item->routeur_id, 
                Routeur::find($item->routeur_id)->nom,
                Routeur::find($item->routeur_id)->prix,
                Resultat::whereBetween('date_envoi', [$from, $to])->where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('routeur_id', $item->routeur_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('routeur_id', $item->routeur_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
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

    public function applyFilterForStatisticsForBases($routeur_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('routeur_id', $routeur_id)->get();

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $routeur_id) {
            $base = new BaseStatsOtherResponse(
                $item->base_id, 
                Base::find($item->base_id)->nom,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()->sum("volume"), 
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

    public function applyFilterForStatisticsForAnnonceurs($routeur_id, $base_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('routeur_id', $routeur_id)
                                    ->where('base_id', $base_id)->get();

        $total = $resultatsFull->uniqueStrict("annonceur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("annonceur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $routeur_id, $base_id) {
            $annonceur = new AnnonceurStatsOtherResponse(
                $item->annonceur_id, 
                Annonceur::find($item->annonceur_id)->nom,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $item->annonceur_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])
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

    public function applyFilterForStatisticsForCampagnes($routeur_id, $base_id, $annonceur_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('routeur_id', $routeur_id)
                                    ->where('base_id', $base_id)
                                    ->where('annonceur_id', $annonceur_id)->get();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $routeur_id, $base_id, $annonceur_id) {
            $campagne = new CampagneStatsOtherResponse(
                $item->campagne_id, 
                Campagne::find($item->campagne_id)->nom,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $base_id)
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('routeur_id', $routeur_id)
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Routeur::create([
            'nom'=>$request->input('nom'), 
            'prix'=>$request->input('prix'),
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
        return response()->json(new RESTResponse(200, "OK", Routeur::find($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateRouteur(Request $request, $id)
    {
        Routeur::where('id',$id)
                ->update([  
                    'nom'=>$request->input('nom'),
                    'prix'=>$request->input('prix'),
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
        Routeur::where('id',$id)
                ->update([  
                    'nom'=>$request->input('nom'),
                    'prix'=>$request->input('prix'),
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
        $routeur = Routeur::find($id);
        if($routeur != null){
            $routeur->delete();
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
    public function deleteRouteur($id)
    {
        $routeur = Routeur::find($id);
        if($routeur != null){
            // $routeur->delete();
            $routeur->deleted = true;
            $routeur->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaiter supprimer n'existe pas dans la Base de données !", null));
    }

    /**
     * Enable the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function enable($id)
    {
        $routeur = Routeur::find($id);
        if($routeur != null){
            $routeur->deleted = false;
            $routeur->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaiter activer n'existe pas dans la Base de données !", null));
    }
}
