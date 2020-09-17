<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annonceur extends Model
{
    protected $fillable = ['nom', 'login', 'password', 'url', 'adresse_facturation', 'email_comptabilite', 'email_direction', 'email_production', 'delai_paiement', 'cree_par', 'modifie_par', 'deleted'];

    public function campagnes()
    {
        return $this->hasMany('App\Campagne');
    }
}
