<?php
namespace Controller;

class AdminUserController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new \Model\User();
        $this->entityName = 'utilisateur';
        $this->viewFolder = 'users';
    }

    protected function validateData($data)
    {
        $errors = [];
        if (empty($data['username'])) {
            $errors[] = "Le nom d'utilisateur est requis.";
        }
        if (empty($data['email'])) {
            $errors[] = "L'email est requis.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'email n'est pas valide.";
        }
        if (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        
        if (!empty($errors)) {
            self::$View->errors = $errors;
            return false;
        }
        
        $validatedData = [
            'username' => $data['username'],
            'email' => $data['email']
        ];
        if (!empty($data['password'])) {
            $validatedData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $validatedData;
    }
}
