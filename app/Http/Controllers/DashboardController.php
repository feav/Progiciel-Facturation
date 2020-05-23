<?php

namespace App\Http\Controllers;

use App\Resultat;
use App\Campagne;
use App\RESTResponse;
use App\Http\Controllers\OthersResponses\DashboardResponse;

class DashboardController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        $ca_journalier = Resultat::where('date_envoi', date('Y-m-d', strtotime('yesterday')))
                                    ->get()
                                    ->sum(function ($resultat) {
                                        return ($resultat->resultat * $resultat->remuneration);
                                    });
                               
     
        $ca_hebdomadaire = Resultat::whereBetween(
                                    'date_envoi',
                                    [
                                        date('Y-m-d', strtotime('Monday this week')),
                                        date('Y-m-d', strtotime('Sunday this week'))
                                    ])
                                    ->get()
                                    ->sum(function ($resultat) {
                                        return ($resultat->resultat * $resultat->remuneration);
                                    });

        $ca_mensuel = Resultat::whereBetween(
                                'date_envoi', 
                                [
                                    date('Y-m-d', strtotime('first day of this month')),
                                    date('Y-m-d', strtotime('last day of this month'))
                                ])
                                ->get()
                                ->sum(function ($resultat) {
                                    return ($resultat->resultat * $resultat->remuneration);
                                });
        
        $ca_annuel = Resultat::whereBetween(
                                'date_envoi', 
                                [
                                    date('Y-m-d', strtotime('first day of January')),
                                    date('Y-m-d', strtotime('last day of December'))
                                ])
                                ->get()
                                ->sum(function ($resultat) {
                                    return ($resultat->resultat * $resultat->remuneration);
                                });

        $volume_journalier = Resultat::where('date_envoi', date('Y-m-d', strtotime('yesterday')))
                                        ->sum('volume');

        $volume_hebdomadaire = Resultat::whereBetween(
                                        'date_envoi', 
                                        [
                                            date('Y-m-d', strtotime('Monday this week')),
                                            date('Y-m-d', strtotime('Sunday this week'))
                                        ])
                                        ->sum('volume');

        $volume_mensuel = Resultat::whereBetween(
                                    'date_envoi', 
                                    [
                                        date('Y-m-d', strtotime('first day of this month')),
                                        date('Y-m-d', strtotime('last day of this month'))
                                    ])
                                    ->sum('volume');

        $volume_annuel = Resultat::whereBetween(
                                    'date_envoi', 
                                    [
                                        date('Y-m-d', strtotime('first day of January')),
                                        date('Y-m-d', strtotime('last day of December'))
                                    ])
                                    ->sum('volume');

        return response()->json( new DashboardResponse(
                                    $ca_journalier,
                                    $ca_hebdomadaire,
                                    $ca_mensuel,
                                    $ca_annuel,
                                    $volume_journalier,
                                    $volume_hebdomadaire,
                                    $volume_mensuel,
                                    $volume_annuel
                                )
                            );

    }
}