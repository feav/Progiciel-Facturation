<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campagne extends Model
{
    protected $fillable = ['nom', 'type_remuneration', 'remuneration', 'annonceur_id', 'cree_par', 'modifie_par', 'deleted'];

    public function annonceur()
    {
        return $this->belongsTo('App\Annonceur');
    }
    
    public function plannings()
    {
        return $this->hasMany('App\Planning');
    }

    public function resultats()
    {
        return $this->hasMany('App\Resultat');
    }
}
