<?php

namespace App\Http\Controllers\OthersResponses;

class RouteurStatsOtherResponse
{
    public $id;
     
    public $nom;

    public $annonceur;

    public $prix;

    public $volume;
     
    public $rem;

    public $resultat;

    public $pa;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new RouteurStatsOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $ann, $pr, $v, $rem, $res, $pa, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->annonceur = $ann;
        $this->prix = $pr;
        $this->volume = $v;
        $this->rem = $rem;
        $this->resultat = $res;
        $this->pa = $pa;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
