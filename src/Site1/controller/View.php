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

        switch(self::$template_engine)
        {
            case 'twig':
                $loader = new \Twig\Loader\FilesystemLoader(DIR_VIEW);
                self::$template_engine_object = new \Twig\Environment($loader, [
                //    'cache' => '../view/cache/',
                ]);
            break;
            case 'smarty':
                self::$template_engine_object = new \Smarty\Smarty();
                self::$template_engine_object->setTemplateDir(DIR_VIEW);
                self::$template_engine_object->setCompileDir(DIR_VIEW.'templates_c/');
            break;
        }
    }

    function __set($var,$value)
    {
        switch(self::$template_engine)
        {
            case 'twig':
                self::$template_params[$var] = $value;
            break;
            case 'smarty':
                self::$template_engine_object->assign($var,$value);
            break;
        }
    }

    function __get($var)
    {
        return self::$template_params[$var];
    }

    function Display($template_name)
    {
        switch(self::$template_engine)
        {
            case 'twig':
                echo self::$template_engine_object->render(
                    $template_name.'.twig', 
                    self::$template_params);
            break;
            case 'smarty':
                self::$template_engine_object->display($template_name.'.tpl');
            break;
        }
    }
}