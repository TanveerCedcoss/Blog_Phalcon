<?php

use Phalcon\Mvc\Controller;


class TestController extends Controller
{
    public function testAction()
    {
        $date = new \App\Components\DateHelper();
        echo $date->test();
    }
}