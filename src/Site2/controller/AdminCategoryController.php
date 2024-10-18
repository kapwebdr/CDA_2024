<?php
namespace Controller;

class AdminCategoryController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new \Model\Category();
        $this->entityName = 'catégorie';
        $this->viewFolder = 'categories';
    }

    protected function validateData($data)
    {
        $errors = [];
        if (empty($data['name'])) {
            $errors[] = "Le nom de la catégorie est requis.";
        }
        
        if (!empty($errors)) {
            self::$View->errors = $errors;
            return false;
        }
        
        return ['name' => $data['name']];
    }
}
