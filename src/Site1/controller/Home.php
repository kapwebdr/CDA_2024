<?php
namespace Controller;
// use \Model\Menu;

/*
$Home = new \Controller\Home();
*/

class Home extends Main
{
    function index($vars=[])
    {
        Main::$View->title      = 'Page d\'accueil';
        Main::$View->h1_title   = 'Page de découverte du site web';
        Main::$View->Display('index');
    }
}