<?php

namespace App;

class RESTPaginateResponse
{
    public $current_page;
     
    public $body;

    public $first_page_url;
     
    public $last_page;

    public $last_page_url;
     
    public $next_page_url;
     
    public $per_page;

    public $prev_page_url;
     
    public $total_current_page;
     
    public $total;

    /**
     * Create a new RESTPaginateResponse instance.
     *
     * @return void
     */
    public function __construct($cp, $b, $fpu, $lp, $lpu, $npu, $pp, $ppu, $ttlcp, $ttl)
    {
        $this->current_page = $cp;
        $this->body = $b;
        $this->first_page_url = $fpu;
        $this->last_page = $lp;
        $this->last_page_url = $lpu;
        $this->next_page_url = $npu;
        $this->per_page = $pp;
        $this->prev_page_url = $ppu;
        $this->total_current_page = $ttlcp;
        $this->total = $ttl;
    }
}
