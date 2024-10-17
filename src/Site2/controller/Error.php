<?php
namespace Controller;

class Error extends Main
{
    public static function PhpError($errno, $errstr, $errfile, $errline)
    {
        var_dump($errstr);
        exit();
        $errorType = 'Erreur PHP';
        $errorMessage = "$errstr dans le fichier $errfile à la ligne $errline";
        self::displayErrorPage($errorType, $errorMessage, $errno);
    }

    public static function PhpFatalError()
    {
        $lastError = error_get_last();
        if ($lastError && $lastError['type'] === E_ERROR) {
            $errorType = 'Erreur fatale PHP';
            $errorMessage = "{$lastError['message']} dans le fichier {$lastError['file']} à la ligne {$lastError['line']}";
            self::displayErrorPage($errorType, $errorMessage, $lastError['type']);
        }
    }
    
    public static function PdoException($exception)
    {
        $errorType = 'Erreur de base de données';
        $errorMessage = $exception->getMessage();
        self::displayErrorPage($errorType, $errorMessage, $exception->getCode());
    }

    public static function HttpError($code)
    {
        $errorType = "Erreur HTTP $code";
        $errorMessage = self::getHttpErrorMessage($code);
        self::displayErrorPage($errorType, $errorMessage, $code);
    }

    private static function displayErrorPage($errorType, $errorMessage, $errorCode)
    {
        $view = new View('twig');
        $view->errorType = $errorType;
        $view->errorMessage = $errorMessage;
        $view->errorCode = $errorCode;
        $view->Display('error');
        exit;
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
