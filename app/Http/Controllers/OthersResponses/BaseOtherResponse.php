<?php

namespace App\Http\Controllers\OthersResponses;

class BaseOtherResponse
{
    public $id;
     
    public $nom;
     
    public $rem;

    public $resultat;

    public $pa;

    /**
     * Create a new BaseOtherResponse instance.
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
