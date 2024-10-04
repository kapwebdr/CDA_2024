<?php
namespace Controller;
/*
$Main = new \Controller\Main();
*/

class Main
{
    protected $View;
    
    public function __construct()
    {
        $this->View = new \Controller\View('twig');

        $menu = new \Model\Menu();
        $menus = $menu->getMenu();

        $this->View->menus      = $menus;
        $this->View->copyright  = COPYRIGHT;
    }
}