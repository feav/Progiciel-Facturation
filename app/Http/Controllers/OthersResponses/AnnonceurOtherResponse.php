<?php

namespace App\Http\Controllers\OthersResponses;

class AnnonceurOtherResponse
{
    public $id;
     
    public $nom;
     
    public $url;

    public $login;

    public $password;

    public $adresse_facturation;

    public $email_comptabilite;

    public $email_direction;
     
    public $email_production;

    public $delai_paiement;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new AnnonceurOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $url, $lgn, $pwd, $af, $ec, $ed, $ep, $dp, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->url = $url;
        $this->login = $lgn;
        $this->password = $pwd;
        $this->adresse_facturation = $af;
        $this->email_comptabilite = $ec;
        $this->email_direction = $ed;
        $this->email_production = $ep;
        $this->delai_paiement = $dp;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
