<?php
if(!isset($_SESSION['language']))
    $_SESSION['language'] = DEFAULT_LANGUAGE;

if(!isset($_GET['url']) || $_GET['url'] == $_GET['app'])
    $_GET['url'] = DEFAULT_HOMEPAGE;

# Initialize global styles and scripts variables, so they can later be used
$styles  = array();
$scripts = array();