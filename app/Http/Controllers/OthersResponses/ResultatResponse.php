<?php

namespace App\Http\Controllers\OthersResponses;

class ResultatResponse
{
    public $id;
     
    public $date;

    public $heure;
     
    public $routeur;

    public $nom_routeur;

    public $base;

    public $nom_base;
     
    public $annonceur;

    public $nom_annonceur;
     
    public $campagne;

    public $nom_campagne;

    public $volume;

    public $resultat;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    public $deleted;

    /**
     * Create a new ResultatResponse instance.
     *
     * @return void
     */
    public function __construct($id, $d, $h, $ro, $nro, $b, $nb, $a, $na, $c, $nc, $v, $re, $cl, $cp, $ml, $mp, $del)
    {
        $this->id = $id;
        $this->date = $d;
        $this->heure = $h;
        $this->routeur = $ro;
        $this->nom_routeur = $nro;
        $this->base = $b;
        $this->nom_base = $nb;
        $this->annonceur = $a;
        $this->nom_annonceur = $na;
        $this->campagne = $c;
        $this->nom_campagne = $nc;
        $this->volume = $v;
        $this->resultat = $re;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
        $this->deleted = $del;
    }
}
