<?php
namespace Model;

class Menu
{
    function getMenu()
    {
        $json = file_get_contents(filename: '../db/menu.json');
        $json = json_decode($json, associative: true);
        return $json;
    }

    function setMenu($menu): true
    {
        $json = json_encode(value: $menu);
        file_put_contents(filename: '../db/menu.json', data: $json);
        return true;
    }
}