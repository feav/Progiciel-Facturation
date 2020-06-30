<?php

namespace App\Http\Controllers\OthersResponses;

class AnnonceurStatsOtherResponse
{
    public $id;

    public $nom;
     
    public $pa;

    public $ca;

    public $pm;

    public $volume;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new AnnonceurStatsOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $ann, $p, $c, $pm, $v, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $ann;
        $this->pa = $p;
        $this->ca = $c;
        $this->pm = $pm;
        $this->volume = $v;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
