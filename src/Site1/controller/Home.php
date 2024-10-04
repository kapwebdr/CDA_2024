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
        $this->View->title      = 'Page d\'accueil';
        $this->View->h1_title   = 'Page de découverte du site web';
        

        $this->View->Display('index');
    }
}