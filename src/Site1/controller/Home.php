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
        echo $this->twig->render('index.twig', [
            'title'=>'Page d\'accueil',
            'h1_title'=>'Page de découverte du site web',
            'menus'=>$this->menus]);
    }
}