<?php
namespace Controller;

class View 
{
    static private $template_params = [];
    static private $template_engine_object = null;
    static private $template_engine = 'twig';

    function __construct($engine=null)
    {   
        if(!is_null($engine))
        {
            self::$template_engine = $engine;
        }

        $loader = new \Twig\Loader\FilesystemLoader(DIR_VIEW);
        self::$template_engine_object = new \Twig\Environment($loader, [
            // 'cache' => DIR_VIEW . 'cache/',
        ]);
    }

    function __set($var, $value)
    {
        self::$template_params[$var] = $value;
    }

    function __get($var)
    {
        return self::$template_params[$var] ?? null;
    }

    function Display($template_name)
    {
        echo self::$template_engine_object->render(
            $template_name . '.twig', 
            self::$template_params
        );
    }
}