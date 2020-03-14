<?php

namespace App\Http\Controllers\OthersResponses;

class AnnonceurOtherResponse
{
    public $id;
     
    public $nom;
     
    public $rem;

    public $resultat;

    public $pa;

    /**
     * Create a new AnnonceurOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $rem, $res, $p)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->rem = $rem;
        $this->resultat = $res;
        $this->pa = $p;
    }
}
