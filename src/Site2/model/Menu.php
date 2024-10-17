<?php
namespace Model;

class Menu
{
    public function getMenu()
    {
        $json = file_get_contents(DIR_BASE . 'db/menu.json');
        return json_decode($json, true);
    }
}
