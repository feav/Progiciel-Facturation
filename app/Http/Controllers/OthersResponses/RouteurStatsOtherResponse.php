<?php

namespace App\Http\Controllers\OthersResponses;

class RouteurStatsOtherResponse
{
    public $id;
     
    public $routeur;

    public $annonceur;

    public $prix;

    public $volume;
     
    public $pa;

    public $ca;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new RouteurStatsOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $r, $ann, $pr, $v, $pa, $c, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->routeur = $r;
        $this->annonceur = $ann;
        $this->prix = $pr;
        $this->volume = $v;
        $this->pa = $pa;
        $this->ca = $c;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
