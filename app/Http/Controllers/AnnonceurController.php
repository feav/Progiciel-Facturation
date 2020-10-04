<?php

namespace App\Http\Controllers;

use App\Annonceur;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\RESTPaginateResponse;
use App\Resultat;
use App\Campagne;
use App\Routeur;
use App\Base;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\OthersResponses\AnnonceurOtherResponse;
use App\Http\Controllers\OthersResponses\AnnonceurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\RouteurStatsOtherResponse;
use App\Http\Controllers\OthersResponses\BaseStatsOtherResponse;
use App\Http\Controllers\OthersResponses\CampagneStatsOtherResponse;
use App\Http\Controllers\OthersResponses\TreeNodeResponse;

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
            $annonceur = new AnnonceurOtherResponse ($item->id, $item->nom, $item->url, $item->login, $item->password, $item->adresse_facturation, $item->email_comptabilite, $item->email_direction, $item->email_production, $item->delai_paiement, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted);
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
            $annonceur = new AnnonceurOtherResponse ($item->id, $item->nom, $item->url, $item->login, $item->password, $item->adresse_facturation, $item->email_comptabilite, $item->email_direction, $item->email_production, $item->delai_paiement, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted);
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
        $annonceurs = Annonceur::where('nom', 'like', '%' . $search_text . '%')
                                ->paginate($per_page);
        $annonceurs->transform(function ($item, $key) {
            $annonceur = new AnnonceurOtherResponse ($item->id, $item->nom, $item->url, $item->login, $item->password, $item->adresse_facturation, $item->email_comptabilite, $item->email_direction, $item->email_production, $item->delai_paiement, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name, $item->deleted);
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
                $item->annonceur_id, 
                Annonceur::find($item->annonceur_id)->nom, 
                Resultat::where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
                date('d-m-Y à H:i:s', strtotime($item->created_at)), 
                User::find($item->cree_par) == null ? null : User::find($item->cree_par)->name, 
                date('d-m-Y à H:i:s', strtotime($item->updated_at)), 
                User::find($item->modifie_par) == null ? null : User::find($item->modifie_par)->name
            );
            //return new TreeNodeResponse($annonceur, array(new TreeNodeResponse($annonceur, null)));
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
    
    public function indexForStatisticsForCampagnes($annonceur_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('annonceur_id', $annonceur_id)->get();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($annonceur_id) {
            $campagne = new CampagneStatsOtherResponse(
                $item->campagne_id, 
                Campagne::find($item->campagne_id)->nom, 
                Resultat::where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('annonceur_id', $annonceur_id)
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
    
    public function indexForStatisticsForRouteurs($annonceur_id, $campagne_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('annonceur_id', $annonceur_id)
                                    ->where('campagne_id', $campagne_id)
                                    ->get();
        // $resultatsFull = Resultat::where('campagne_id', $campagne_id)
        //                             ->get();

        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($annonceur_id, $campagne_id) {
            $routeur = new RouteurStatsOtherResponse(
                $item->routeur_id, 
                Routeur::find($item->routeur_id)->nom, 
                Routeur::find($item->routeur_id)->prix,
                Resultat::where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('annonceur_id', $annonceur_id)
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
    
    public function indexForStatisticsForBases($annonceur_id, $campagne_id, $routeur_id, $page = 1, $per_page = 15){
        $resultatsFull = Resultat::where('annonceur_id', $annonceur_id)
                                    ->where('campagne_id', $campagne_id)
                                    ->where('routeur_id', $routeur_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use ($annonceur_id, $campagne_id, $routeur_id) {
            $base = new BaseStatsOtherResponse(
                $item->base_id, 
                Base::find($item->base_id)->nom, 
                Resultat::where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::where('annonceur_id', $annonceur_id)
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
                $item->annonceur_id, 
                Annonceur::find($item->annonceur_id)->nom, 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])->where('annonceur_id', $item->annonceur_id)->get()->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])->where('annonceur_id', $item->annonceur_id)->get()->sum("volume"), 
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

    public function applyFilterForStatisticsForCampagnes($annonceur_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('annonceur_id', $annonceur_id)->get();

        $total = $resultatsFull->uniqueStrict("campagne_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("campagne_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $annonceur_id) {
            $campagne = new CampagneStatsOtherResponse(
                $item->campagne_id, 
                Campagne::find($item->campagne_id)->nom, 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $item->campagne_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])
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

    public function applyFilterForStatisticsForRouteurs($annonceur_id, $campagne_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('annonceur_id', $annonceur_id)
                                    ->where('campagne_id', $campagne_id)
                                    ->get();

        $total = $resultatsFull->uniqueStrict("routeur_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("routeur_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $annonceur_id, $campagne_id) {
            $routeur = new RouteurStatsOtherResponse(
                $item->routeur_id, 
                Routeur::find($item->routeur_id)->nom,
                Routeur::find($item->routeur_id)->prix,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()->sum("volume"), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $item->routeur_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
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

    public function applyFilterForStatisticsForBases($annonceur_id, $campagne_id, $routeur_id, $page = 1, $per_page = 15, Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));

        $resultatsFull = Resultat::whereBetween('date_envoi', [$from, $to])
                                    ->where('annonceur_id', $annonceur_id)
                                    ->where('campagne_id', $campagne_id)
                                    ->where('routeur_id', $routeur_id)->get();

        $total = $resultatsFull->uniqueStrict("base_id")->count();
        $totalVolume = $resultatsFull->sum("volume");
        $totalPA = $resultatsFull->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; });
        $totalCA = $resultatsFull->sum(function ($item) { return $item->remuneration * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $resultats = $resultatsFull->uniqueStrict("base_id")->slice(($page - 1) * $per_page, $per_page);

        $resultats->transform(function ($item, $key) use($from, $to, $annonceur_id, $campagne_id, $routeur_id) {
            $base = new BaseStatsOtherResponse(
                $item->base_id, 
                Base::find($item->base_id)->nom,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return Routeur::find($item->routeur_id)->prix * $item->volume; }), 
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('annonceur_id', $annonceur_id)
                        ->where('campagne_id', $campagne_id)
                        ->where('routeur_id', $routeur_id)
                        ->where('base_id', $item->base_id)->get()
                        ->sum(function ($item) { return $item->remuneration * $item->resultat; }),
                0,
                Resultat::whereBetween('date_envoi', [$from, $to])
                        ->where('annonceur_id', $annonceur_id)
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
            // $annonceur->delete();
            $annonceur->deleted = true;
            $annonceur->save();
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
        $annonceur = Annonceur::find($id);
        if($annonceur != null){
            $annonceur->deleted = false;
            $annonceur->save();
            return response()->json(new RESTResponse(200, "OK", null));
        }else
            return response()->json(new RESTResponse(404, "L'élément que vous souhaiter activer n'existe pas dans la Base de données !", null));
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
