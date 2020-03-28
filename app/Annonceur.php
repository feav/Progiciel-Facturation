<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Annonceur extends Model
{
    protected $fillable = ['nom', 'url', 'adresse_facturation', 'email_comptabilite', 'email_direction', 'email_production', 'delai_paiement', 'cree_par', 'modifie_par'];

    public function campagnes()
    {
        return $this->hasMany('App\Campagne');
    }
}
