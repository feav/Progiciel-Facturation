<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Routeur extends Model
{
    protected $fillable = ['nom', 'prix'];

    public function bases()
    {
        return $this->hasMany('App\Base');
    }
}
