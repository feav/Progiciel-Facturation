<?php

namespace App;

class RESTResponse
{
    public $code;
     
    public $message;
     
    public $body;

    /**
     * Create a new RESTResponse instance.
     *
     * @return void
     */
    public function __construct($c, $m, $b)
    {
        $this->code = $c;
        $this->message = $m;
        if($b != null){
            $this->body = [];
            $b->each(function ($item, $key) {
                array_push($this->body, $item);
            });
        }else
            $this->body = $b;
    }
}
