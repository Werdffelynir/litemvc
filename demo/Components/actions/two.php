<?php

class Two extends Controller
{
    public function run()
    {
        // Do something...
        // You can use class Controlleri
        return '<img src="'. App::$urlTheme.'/images/demo_code.png" alt=""/>';
    }
}

//echo (new Two())->run();
$twoAction = new Two();
echo $twoAction->run();

//