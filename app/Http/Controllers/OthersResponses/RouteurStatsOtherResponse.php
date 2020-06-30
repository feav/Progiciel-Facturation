<?php

namespace App\Http\Controllers\OthersResponses;

class RouteurStatsOtherResponse
{
    public $id;
     
    public $nom;

    public $prix;

    public $volume;
     
    public $pa;

    public $ca;

    public $pm;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new RouteurStatsOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $r, $pr, $v, $pa, $c, $pm, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $r;
        $this->prix = $pr;
        $this->volume = $v;
        $this->pa = $pa;
        $this->ca = $c;
        $this->pm = $pm;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
