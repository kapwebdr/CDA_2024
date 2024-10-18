<?php
namespace Controller;

class Account extends Main
{
    public function myAccount()
    {
        if (!self::$User) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            $userModel = new \Model\User();
            
            if (!$userModel->verifyPassword($currentPassword, self::$User['password'])) {
                self::$View->error = 'Mot de passe actuel incorrect.';
            } elseif ($newPassword !== $confirmPassword) {
                self::$View->error = 'Les nouveaux mots de passe ne correspondent pas.';
            } else {
                $updateData = [
                    'username' => $username,
                    'email' => $email
                ];
                if (!empty($newPassword)) {
                    $updateData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                if ($userModel->updateUser(self::$User['id'], $updateData)) {
                    self::$User = $userModel->getUserById(self::$User['id']);
                    self::$View->user = self::$User;
                    self::$View->success = 'Compte mis à jour avec succès.';
                } else {
                    self::$View->error = 'Erreur lors de la mise à jour du compte.';
                }
            }
        }

        self::$View->title = 'Mon compte';
        self::$View->Display('account');
    }

    public function myProfile()
    {
        if (!self::$User) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $birthdate = $_POST['birthdate'] ?? '';
            $address = $_POST['address'] ?? '';
            $address2 = $_POST['address2'] ?? '';
            $postalCode = $_POST['postal_code'] ?? '';
            $city = $_POST['city'] ?? '';
            $country = $_POST['country'] ?? '';

            $userModel = new \Model\User();
            $updateData = [
                'birthdate' => $birthdate,
                'address' => $address,
                'address2' => $address2,
                'postal_code' => $postalCode,
                'city' => $city,
                'country' => $country
            ];

            // Gestion de l'upload d'avatar
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatar = $this->uploadAvatar($_FILES['avatar']);
                if ($avatar) {
                    $updateData['avatar'] = $avatar;
                }
            }

            if ($userModel->updateUser(self::$User['id'], $updateData)) {
                self::$User = $userModel->getUserById(self::$User['id']);
                self::$View->user = self::$User;
                self::$View->success = 'Profil mis à jour avec succès.';
            } else {
                self::$View->error = 'Erreur lors de la mise à jour du profil.';
            }
        }

        self::$View->title = 'Mon profil';
        self::$View->Display('profile');
    }

    private function uploadAvatar($file)
    {
        $uploadDir = DIR_BASE . 'public/uploads/avatars/';
        $fileName = uniqid() . '_' . $file['name'];
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            return '/uploads/avatars/' . $fileName;
        }

        return false;
    }
}
