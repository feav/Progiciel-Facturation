<?php

namespace App\Http\Controllers\OthersResponses;

class RouteurStatsOtherResponse
{
    public $id;
     
    public $nom;

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
    public function __construct($id, $n, $ann, $pr, $v, $pa, $c, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $n;
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
