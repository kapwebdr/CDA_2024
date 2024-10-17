<?php
namespace Controller;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Main
{
    static $View;
    static $Logger;
    static $Mailer;

    static function Init()
    {
        self::$View = new \Controller\View('twig');

        $menu = new \Model\Menu();
        $menus = $menu->getMenu();

        self::$View->menus      = $menus;
        self::$View->copyright  = COPYRIGHT;

        self::$Logger = new Logger('cda_log');
        self::$Logger->pushHandler(
            new StreamHandler(DIR_BASE.'/private/logs/cda.log',Level::Warning)
        );

        self::$Mailer = new PhpMailer(true);
    }

    public static function sendMail(
        $to,
        $subject,
        $body
    )
    {
        self::$Mailer->SMTPDebug = (SMTP_DEBUG)?SMTP::DEBUG_SERVER:null;
        self::$Mailer->isSMTP();
        self::$Mailer->Host = SMTP_HOST;
        self::$Mailer->SMTPAuth = true;
        self::$Mailer->Username = SMTP_USER;
        self::$Mailer->Password = SMTP_PASSWORD;
        self::$Mailer->Port     = SMTP_PORT;

        self::$Mailer->setFrom(SMTP_USER);
        self::$Mailer->addAddress($to);
        self::$Mailer->Subject = $subject;
        self::$Mailer->Body = $body;
        self::$Mailer->send();
    }
    
    public function __construct()
    {
        self::Init();
    }
}