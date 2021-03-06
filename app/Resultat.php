<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Resultat extends Model
{
    protected $fillable = ['annonceur_id', 'routeur_id', 'base_id', 'campagne_id', 'volume', 'date_envoi', 'heure_envoi', 'resultat', 'cree_par', 'modifie_par', 'deleted', 'shown'];

    public function base()
    {
        return $this->belongsTo('App\Base');
    }

    public function campagne()
    {
        return $this->belongsTo('App\Campagne');
    }
}
