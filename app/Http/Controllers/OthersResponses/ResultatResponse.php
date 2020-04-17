<?php

namespace App\Http\Controllers\OthersResponses;

class ResultatResponse
{
    public $id;
     
    public $date;

    public $heure;
     
    public $routeur;

    public $base;
     
    public $annonceur;
     
    public $campagne;

    public $volume;

    public $resultat;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new ResultatResponse instance.
     *
     * @return void
     */
    public function __construct($id, $d, $h, $ro, $b, $a, $c, $v, $re, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->date = $d;
        $this->heure = $h;
        $this->routeur = $ro;
        $this->base = $b;
        $this->annonceur = $a;
        $this->campagne = $c;
        $this->volume = $v;
        $this->resultat = $re;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}