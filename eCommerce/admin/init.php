<?php

// Routes
include 'connect.php';
$tpl = 'includes/templates/';
$lang = 'includes/languages/';
$func = 'includes/functions/';
// include the important files
include $func . 'functions.php';
include $lang . 'english.php';
include $tpl . 'header.php';


// include navbar on all pages expect the one  with $noNavbar vairable
if (!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}

?>