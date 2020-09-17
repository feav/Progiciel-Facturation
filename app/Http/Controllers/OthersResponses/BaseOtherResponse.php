<?php

namespace App\Http\Controllers\OthersResponses;

class BaseOtherResponse
{
    public $id;
     
    public $nom;
     
    public $routeur;

    public $nom_routeur;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    public $deleted;

    /**
     * Create a new BaseOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $ro, $nro, $cl, $cp, $ml, $mp, $del)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->routeur = $ro;
        $this->nom_routeur = $nro;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
        $this->deleted = $del;
    }
}
