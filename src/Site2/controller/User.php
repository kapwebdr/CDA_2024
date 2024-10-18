<?php
namespace Controller;

class User extends Main
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new \Model\User();
            try {
                $userId = $userModel->create($username, $email, $password);
                // Ajouter le profil 'User' par défaut
                $userModel->addUserProfile($userId, 3); // 3 est l'ID du profil 'User'
                Session::Set('user_id', $userId);
                self::$User = $userModel->getUserById($userId);
                self::$View->user = self::$User;
                header('Location: /');
                exit;
            } catch (\PDOException $e) {
                self::$View->error = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }

        self::$View->title = "Inscription";
        self::$View->Display('register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new \Model\User();
            $user = $userModel->getUserByUsername($username);

            if ($user && $userModel->verifyPassword($password, $user['password'])) {
                Session::Set('user_id', $user['id']);
                self::$User = $user;
                self::$View->user = $user;
                
                $redirectUrl = Session::Get('redirect_after_login', '/');
                Session::Delete('redirect_after_login');
                
                header('Location: ' . $redirectUrl);
                exit;
            } else {
                self::$View->error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }

        self::$View->title = "Connexion";
        self::$View->Display('login');
    }

    public function logout()
    {
        Session::Destroy();
        self::$User = null;
        self::$View->user = null;
        header('Location: /');
        exit;
    }
}
