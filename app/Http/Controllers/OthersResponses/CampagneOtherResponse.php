<?php

namespace App\Http\Controllers\OthersResponses;

class CampagneOtherResponse
{
    public $id;
     
    public $nom;
     
    public $type_remuneration;

    public $remuneration;
     
    public $annonceur;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new CampagneOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $tr, $rem, $an, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->type_remuneration = $tr;
        $this->remuneration = $rem;
        $this->annonceur = $an;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
