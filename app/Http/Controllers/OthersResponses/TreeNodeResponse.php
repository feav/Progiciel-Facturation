<?php

namespace App\Http\Controllers\OthersResponses;

class TreeNodeResponse
{
    public $data;

    public $children;

    /**
     * Create a new BaseOtherResponse instance.
     *
     * @return void
     */
    public function __construct($d, $c)
    {
        $this->data = $d;
        $this->children = $c;
    }
}
