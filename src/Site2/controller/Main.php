<?php
namespace Controller;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

use \Controller\Cart;
class Main
{
    static $View;
    static $Logger;
    static $Mailer;
    static $User = null;
    static $UserPermissions = [];
    static $Editor = null;

    static function Init()
    {
        self::$View = new \Controller\View('twig');

        $menu = new \Model\Menu();
        $menus = $menu->getMenu();

        self::$View->menus      = $menus;
        self::$View->copyright  = COPYRIGHT;
        self::$View->cartCount  = Cart::getCartItemCount();
        self::$Logger = new Logger('cda_log');
        self::$Logger->pushHandler(
            new StreamHandler(DIR_PRIVATE.'/logs/cda.log',Level::Warning)
        );

        self::$Mailer = new PhpMailer(true);

        // Initialiser l'utilisateur s'il est connecté
        $userId = Session::Get('user_id');
        if ($userId) {
            $userModel = new \Model\User();
            self::$User = $userModel->getUserById($userId);
            self::$UserPermissions = $userModel->getUserPermissions($userId);
            // Vérifier si l'utilisateur est un éditeur
            $editorModel = new \Model\Editor();
            self::$Editor = $editorModel->getEditorByUserId($userId);
        }

        // Passer l'utilisateur et l'éditeur à la vue
        self::$View->user = self::$User;
        self::$View->editor = self::$Editor;
    }

    public static function hasPermission($permission)
    {
        if (!self::$User) {
            return false;
        }
        
        foreach (self::$UserPermissions as $userPermission) {
            if ($userPermission['name'] === $permission) {
                return true;
            }
        }
        
        return false;
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
