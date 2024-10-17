<?php
namespace Controller;

class Auth extends Main
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new \Model\User();
            $user = $userModel->getUserByUsername($username);

            if ($user && $userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['profiles'] = $userModel->getUserProfiles($user['id']);
                $_SESSION['permissions'] = $userModel->getUserPermissions($user['id']);
                header('Location: /');
                exit;
            } else {
                Main::$View->error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        }

        Main::$View->title = 'Connexion';
        Main::$View->Display('login');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new \Model\User();
            try {
                $userId = $userModel->createUser($username, $email, $password);
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                header('Location: /');
                exit;
            } catch (\PDOException $e) {
                Main::$View->error = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }

        Main::$View->title = 'Inscription';
        Main::$View->Display('register');
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}
