<?php

namespace App\Http\Controllers\OthersResponses;

class RouteurOtherResponse
{
    public $id;
     
    public $nom;

    public $prix;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    public $deleted;

    /**
     * Create a new RouteurOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $pr, $cl, $cp, $ml, $mp, $del)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->prix = $pr;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
        $this->deleted = $del;
    }
}
