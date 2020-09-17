<?php

namespace App\Http\Controllers\OthersResponses;

class UserResponse
{
    public $id;
     
    public $nom;
     
    public $email;

    public $password;
     
    public $role;

    public $nom_role;

    public $cree_le;

    public $cree_par;

    public $modifie_le;

    public $modifie_par;

    public $deleted;

    /**
     * Create a new CampagneOtherResponse instance.
     *
     * @return void
     */
    public function __construct($id, $n, $em, $pw, $rol, $nrol, $cl, $cp, $ml, $mp, $del)
    {
        $this->id = $id;
        $this->nom = $n;
        $this->email = $em;
        $this->password = $pw;
        $this->role = $rol;
        $this->nom_role = $nrol;
        $this->cree_le = $cl;
        $this->cree_par = $cp;
        $this->modifie_le = $ml;
        $this->modifie_par = $mp;
        $this->deleted = $del;
    }
}
