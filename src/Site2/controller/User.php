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
            $userId = $userModel->createUser($username, $email, $password);

            if ($userId) {
                // Ajouter le profil 'User' par défaut
                $userModel->addUserProfile($userId, 3); // 3 est l'ID du profil 'User'
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                header('Location: /');
                exit;
            } else {
                Main::$View->error = "Erreur lors de l'inscription.";
            }
        }

        Main::$View->title = "Inscription";
        Main::$View->Display('register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new \Model\User();
            $user = $userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: /');
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
        session_destroy();
        header('Location: /');
        exit;
    }
}
