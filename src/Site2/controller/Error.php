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
    $errfile,$errline,$errcontext = null)
    {
        $errorType = 'Erreur PHP';
        $errorMessage = "$errstr dans le fichier $errfile à la ligne $errline";
        self::displayError($errno, $errorType, $errorMessage);
        self::sendLogError($errno, $errorMessage);
    }

    static function PhpFatalError()
    {
        $lastError = error_get_last();
        if ($lastError && $lastError['type'] === E_ERROR) {
            $errorType = 'Erreur fatale PHP';
            $errorMessage = "{$lastError['message']} dans le fichier {$lastError['file']} à la ligne {$lastError['line']}";
            self::displayError($lastError['type'], $errorType, $errorMessage);
            self::sendLogError($lastError['type'], $errorMessage);
        }
    }
    
    static function PdoException($exception)
    {
        $errorType = 'Erreur de base de données';
        $errorMessage = $exception->getMessage();
        var_dump($errorMessage);
        //self::displayError($exception->getCode(), $errorType, $errorMessage);
        //self::sendLogError($exception->getCode(), $errorMessage);
    }

    /*
    - Créé un template twig pour afficher les erreurs Http
    - Modifier HttpError pour afficher le template twig.
    */

    static function HttpError($code)
    {
        http_response_code($code);
        $errorType = "Erreur HTTP $code";
        $errorMessage = self::getHttpErrorMessage($code);
        self::displayError($code, $errorType, $errorMessage);
        self::sendLogError($code, 'Erreur Http.');
    }

    static function UserError($code,$message)
    {
        self::displayError($code, 'Erreur utilisateur', $message);
        self::sendLogError($code, $message);
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

    static function displayError($code, $errorType, $message='')
    {
        if ($_ENV['DISPLAY_ERRORS'] === 'true' || $errorType === 'Erreur utilisateur') {
            Main::Init();
            Main::$View->errorCode = $code;
            Main::$View->errorType = $errorType;
            Main::$View->errorMessage = $message;
            Main::$View->Display('error');
        } else {
            self::HttpError(500);
        }
    }

    private static function getHttpErrorMessage($code)
    {
        $messages = [
            400 => 'Mauvaise requête',
            401 => 'Non autorisé',
            403 => 'Accès interdit',
            404 => 'Page non trouvée',
            405 => 'Méthode non autorisée',
            500 => 'Erreur interne du serveur',
        ];
        return $messages[$code] ?? 'Une erreur est survenue';
    }
}
