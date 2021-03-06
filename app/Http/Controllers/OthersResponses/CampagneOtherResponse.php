<?php

namespace App\Http\Controllers\OthersResponses;

class CampagneOtherResponse
{
    public $id;
     
    public $nom;
     
    public $type_remuneration;

    public $remuneration;
     
    public $annonceur;

    public $nom_annonceur;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    public $deleted;

    /**
     * Create a new CampagneOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $tr, $rem, $an, $nan, $cl, $cp, $ml, $mp, $del)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->type_remuneration = $tr;
        $this->remuneration = $rem;
        $this->annonceur = $an;
        $this->nom_annonceur = $nan;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
        $this->deleted = $del;
    }
}
