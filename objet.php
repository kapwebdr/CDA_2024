<?php

class Animal{

    // protected | private | public
    // static 
    static $yeux=true;
    public $race;

    // protected | private | public ( static ) function ma_function()
    public function ma_function()
    {
        echo $this->race;
        echo self::$yeux;
    }
    static public function ma_function_static()
    {
        echo self::$yeux;
    }
}

$Chien = new Animal();
$Chien->race = 'Chien';
$Chien->ma_function();

echo Animal::ma_function_static();
echo Animal::$yeux;

$Chat = new Animal();
$Chat->race = 'Chat';
$Chat->ma_function();



