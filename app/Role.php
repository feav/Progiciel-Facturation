<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['intitule', 'cree_par', 'modifie_par', 'deleted'];

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
