<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Routeur;
use App\Annonceur;
use Illuminate\Http\Request;
use App\RESTResponse;
use App\RESTPaginateResponse;
use App\Resultat;
use App\Campagne;
use App\User;
use App\Http\Controllers\OthersResponses\RouteurOtherResponse;
use App\Http\Controllers\OthersResponses\RouteurStatsOtherResponse;

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
            $routeur = new RouteurOtherResponse ($item->id, $item->nom, $item->prix, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name);
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
            $routeur = new RouteurOtherResponse ($item->id, $item->nom, $item->prix, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name );
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
        $routeurs = Routeur::where('nom', 'like', '%' . $search_text . '%')->paginate($per_page);
        $routeurs->transform(function ($item, $key) {
            $routeur = new RouteurOtherResponse ($item->id, $item->nom, $item->prix, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name );
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
    public function indexForStatistics()
    {
        //$routeursFull = Resultat::paginate($per_page);
        $routeursFull = Resultat::all();

        $totalVolume = $routeursFull->sum("volume");
        
        $routeursFull->transform(function ($item, $key) {
            $routeur = new RouteurStatsOtherResponse ($item->id, Routeur::find($item->routeur_id)->nom, Annonceur::find($item->annonceur_id), Routeur::find($item->routeur_id)->prix, $item->volume, $item->remuneration, $item->resultat, Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name );
            return $routeur;
        });
        $routeurs = $routeursFull->uniqueStrict("nom");
        $routeurs->each(function ($item, $key) use($routeursFull) {
            $item->volume = $routeursFull->where('nom', $item->nom)->sum("volume");
            $item->resultat = $routeursFull->where('nom', $item->nom)->sum("resultat");
            $item->pa = $routeursFull->where('nom', $item->nom)->sum("pa");
        });
        
        $totalPA = $routeurs->sum("pa");
        $totalCA = $routeurs->sum(function ($item) { return $item->rem * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $response = array(  'totalVolume'=>$totalVolume, 
                            'totalPA'=>$totalPA,
                            'totalCA'=>$totalCA,
                            'totalMarge'=>$totalMarge,
                            'data'=>new RESTResponse(200, "OK", $routeurs));
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
            $routeur = new RouteurStatsOtherResponse ($item->id, Routeur::find($item->routeur_id)->nom, Annonceur::find($item->annonceur_id), Routeur::find($item->routeur_id)->prix, $item->volume, $item->remuneration, $item->resultat, Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name );
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
    public function applyFilterForStatistics(Request $request){
        $from = date('Y-m-d', strtotime($request->filtre_date_debut));
        $to = date('Y-m-d', strtotime($request->filtre_date_fin));
        $routeursFull = Resultat::whereBetween('date_envoi', [$from, $to])->get();

        $totalVolume = $routeursFull->sum("volume");
        
        $routeursFull->transform(function ($item, $key) {
            $routeur = new RouteurStatsOtherResponse ($item->id, Routeur::find($item->routeur_id)->nom, Annonceur::find($item->annonceur_id), Routeur::find($item->routeur_id)->prix, $item->volume, $item->remuneration, $item->resultat, Routeur::find($item->routeur_id)->prix * $item->volume, date('d-m-Y à H:i:s', strtotime($item->created_at)), User::find($item->cree_par)->name, date('d-m-Y à H:i:s', strtotime($item->updated_at)), User::find($item->modifie_par)->name );
            return $routeur;
        });
        $routeurs = $routeursFull->uniqueStrict("nom");
        $routeurs->each(function ($item, $key) use($routeursFull) {
            $item->volume = $routeursFull->where('nom', $item->nom)->sum("volume");
            $item->resultat = $routeursFull->where('nom', $item->nom)->sum("resultat");
            $item->pa = $routeursFull->where('nom', $item->nom)->sum("pa");
        });
        
        $totalPA = $routeurs->sum("pa");
        $totalCA = $routeurs->sum(function ($item) { return $item->rem * $item->resultat; });
        $totalMarge = $totalCA - $totalPA;

        $response = array(  'totalVolume'=>$totalVolume, 
                            'totalPA'=>$totalPA,
                            'totalCA'=>$totalCA,
                            'totalMarge'=>$totalMarge,
                            'data'=>new RESTResponse(200, "OK", $routeurs));
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
}
