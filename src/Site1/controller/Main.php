<?php
namespace Controller;

class Main
{
    static $View;

    static function Init()
    {
        self::$View = new \Controller\View('twig');

        $menu = new \Model\Menu();
        $menus = $menu->getMenu();

        self::$View->menus      = $menus;
        self::$View->copyright  = COPYRIGHT;
    }
    
    public function __construct()
    {
        self::Init();
    }
}