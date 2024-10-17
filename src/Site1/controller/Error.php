<?php
namespace Controller;
use \Controller\Main;

/*
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
        self::sendLogError($code,'Erreur Http.');
    }

    static function UserError($code,$message)
    {
        self::sendLogError($code,$message);
    }

    static function sendMailError($code,$message='')
    {
        Main::Init();
        Main::sendMail('debug@kapweb.com','Erreur '.$code,$code.'-->'.$message);
    }

    static function sendLogError($code,$message='')
    {
        Main::Init();
        Main::$Logger->error($code.':'.$message);
    }

    static function displayError($code,$message='')
    {
        Main::Init();
        Main::$View->errorCode = $code;
        Main::$View->errorMessage = $message;
        Main::$View->Display('error');
    }

}