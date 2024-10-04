<?php
namespace Controller;

Class Error
{
    static function PhpError($errno,$errstr,
    $errfile,$errline,$errcontext)
    {

    }

    static function PhpFatalError()
    {
        $lastError = error_get_last();
    }
    
    static function PdoException($exception)
    {

    }

    static function HttpError($code)
    {
        switch ($code)
        {
            case '404':
                echo 'Erreur 404 ❤️';
            break;
            case '405':
                echo 'Erreur 405 ❤️❤️❤️';
            break;
        }
    }

    static function UserError()
    {
        
    }
}