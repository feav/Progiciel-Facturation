<?php

namespace App\Http\Controllers\OthersResponses;

class PlanningResponse
{
    public $id;
     
    public $date;

    public $heure;
     
    public $routeur;

    public $base;
     
    public $annonceur;
     
    public $campagne;

    public $volume;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new PlanningResponse instance.
     *
     * @return void
     */
    public function __construct($id, $d, $h, $ro, $b, $a, $c, $v, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->date = $d;
        $this->heure = $h;
        $this->routeur = $ro;
        $this->base = $b;
        $this->annonceur = $a;
        $this->campagne = $c;
        $this->volume = $v;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
