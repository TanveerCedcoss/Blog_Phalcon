<?php

namespace App\Components;
use Phalcon\Escaper;

class MyEscaper
{
    public function sanitize($val)
    {
        $escaper = new Escaper();
        $santzVal = $escaper->escapeHtml($val);
        return $santzVal;
        
    }
}