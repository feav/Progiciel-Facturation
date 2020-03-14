<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Base extends Model
{
    protected $fillable = ['nom', 'routeur_id'];

    public function routeur()
    {
        return $this->belongsTo('App\Routeur');
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
