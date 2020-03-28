<?php

namespace App\Http\Controllers\OthersResponses;

class UserResponse
{
    public $id;
     
    public $nom;
     
    public $email;

    public $password;
     
    public $role;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    /**
     * Create a new CampagneOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $em, $pw, $rol, $cl, $cp, $ml, $mp)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->email = $em;
        $this->password = $pw;
        $this->role = $rol;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
    }
}
