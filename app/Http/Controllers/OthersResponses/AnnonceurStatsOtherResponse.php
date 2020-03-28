<?php

namespace App\Http\Controllers\OthersResponses;

class AnnonceurStatsOtherResponse
{
    public $id;
     
    public $nom;

    public $annonceur;
     
    public $rem;

    public $resultat;

    public $pa;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new AnnonceurStatsOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $ann, $rem, $res, $p, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->annonceur = $ann;
        $this->rem = $rem;
        $this->resultat = $res;
        $this->pa = $p;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
