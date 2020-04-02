<?php

namespace App\Http\Controllers\OthersResponses;

class DashboardResponse
{
    public $ca_journalier;
     
    public $ca_hebdomadaire;

    public $ca_mensuel;
     
    public $ca_annuel;

    public $volume_journalier;

    public $volume_hebdomadaire;

    public $volume_mensuel;

    public $volume_annuel;

    /**
     * Create a new DasboardResponse instance.
     *
     * @return void
     */
    public function __construct($ca_j, $ca_h, $ca_m, $ca_a, $v_j, $v_h, $v_m, $v_a)
    {
        $this->ca_journalier = $ca_j;
        $this->ca_hebdomadaire = $ca_h;
        $this->ca_mensuel = $ca_m;
        $this->ca_annuel = $ca_a;
        $this->volume_journalier = $v_j;
        $this->volume_hebdomadaire = $v_h;
        $this->volume_mensuel = $v_m;
        $this->volume_annuel = $v_a;
    }
}
