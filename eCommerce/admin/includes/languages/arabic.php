<?php

function lang($phrase)
{
    static $lang = array(
    'MESSAGE' => 'Welcome in Arabic',
    'ADMIN' => 'Arabic Admin',

    );
    return $lang[$phrase];
}


?>