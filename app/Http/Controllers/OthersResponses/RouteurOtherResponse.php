<?php

namespace App\Http\Controllers\OthersResponses;

class RouteurOtherResponse
{
    public $id;
     
    public $nom;

    public $prix;

    public $volume;
     
    public $rem;

    public $resultat;

    public $pa;

    /**
     * Create a new RouteurOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $pr, $v, $rem, $res, $pa)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->prix = $pr;
        $this->volume = $v;
        $this->rem = $rem;
        $this->resultat = $res;
        $this->pa = $pa;
    }
}
