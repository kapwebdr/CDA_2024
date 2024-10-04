<?php
namespace Controller;
/*
$Main = new \Controller\Main();
*/

class Main
{
    protected $twig;
    protected $menus;
    
    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader('../view/');
        $this->twig = new \Twig\Environment($loader, [
        //    'cache' => '../view/cache/',
        ]);

        $menu = new \Model\Menu();
        $this->menus = $menu->getMenu();
    }
}