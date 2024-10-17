<?php
namespace Controller;
use Controller\Cart;
class Main
{
    static public $View;
    
    static function Init()
    {
        self::$View = new \Controller\View('twig');

        $menu = new \Model\Menu();
        $menus = $menu->getMenu();

        self::$View->menus = $menus;
        self::$View->copyright = COPYRIGHT;
        self::$View->user = [
            'id' => $_SESSION['user_id'] ?? null,
            'username' => $_SESSION['username'] ?? null,
        ];
        self::$View->cartCount = Cart::getCartItemCount();
    }
    
    public function __construct()
    {
        self::Init();
    }
}
