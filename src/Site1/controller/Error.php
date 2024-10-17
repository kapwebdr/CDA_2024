<?php
namespace Controller;
use \Controller\Main;

/*

    - Monolog       
    - PhpMailer

    -> Intégrer Monolog ainsi que PhpMailer dans le Main en static
    -> Dans config.php ajouter des éléments de configuration (phpMailer)
    -> Utiliser dans Error avec sendMailError() et sendLogError() 
*/

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

    /*
    - Créé un template twig pour afficher les erreurs Http
    - Modifier HttpError pour afficher le template twig.
    */

    static function HttpError($code)
    {
        http_response_code($code);
        self::displayError($code);
    }

    static function displayError($code,$message='')
    {
        Main::Init();
        Main::$View->errorCode = $code;
        Main::$View->errorMessage = $message;
        Main::$View->Display('error');
    }

    static function UserError()
    {
        
    }
}