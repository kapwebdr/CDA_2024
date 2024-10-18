<?php
namespace Controller;

class AdminEditorController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new \Model\Editor();
        $this->entityName = 'éditeur';
        $this->viewFolder = 'editors';
    }

    protected function validateData($data)
    {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = "Le nom de l'éditeur est requis.";
        }
        if (empty($data['user_id'])) {
            $errors[] = "L'utilisateur associé est requis.";
        }
        
        if (!empty($errors)) {
            self::$View->errors = $errors;
            return false;
        }
        
        return [
            'name' => $data['name'],
            'user_id' => $data['user_id']
        ];
    }
}
