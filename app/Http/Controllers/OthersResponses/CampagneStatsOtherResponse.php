<?php

namespace App\Http\Controllers\OthersResponses;

class CampagneStatsOtherResponse
{
    public $id;
     
    public $nom;

    public $annonceur;
     
    public $pa;

    public $ca;

    public $volume;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new CampagneStatsOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $ann, $p, $c, $v, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->annonceur = $ann;
        $this->pa = $p;
        $this->ca = $c;
        $this->volume = $v;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
