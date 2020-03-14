<?php

namespace App\Http\Controllers\OthersResponses;

class ResultatResponse
{
    public $id;
     
    public $date;
     
    public $routeur;

    public $base;
     
    public $annonceur;
     
    public $campagne;

    public $volume;

    public $resultat;

    /**
     * Create a new ResultatResponse instance.
     *
     * @return void
     */
    public function __construct($id, $d, $ro, $b, $a, $c, $v, $re)
    {
        $this->id = $id;
        $this->date = $d;
        $this->routeur = $ro;
        $this->base = $b;
        $this->annonceur = $a;
        $this->campagne = $c;
        $this->volume = $v;
        $this->resultat = $re;
    }
}
