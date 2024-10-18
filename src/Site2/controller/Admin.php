<?php
namespace Controller;

class Admin extends Main
{
    public function __construct()
    {
        parent::__construct();
        if (!self::$User || !in_array('Administrateur', explode(',', self::$User['profiles']))) {
            \Controller\Error::HttpError(403);
        }
        self::$View->is_admin_page = true;
    }

    public function index()
    {
        self::$View->title = 'Administration';
        self::$View->Display('admin/index');
    }

    public function categories()
    {
        $categoryModel = new \Model\Category();
        self::$View->categories = $categoryModel->getAll();
        self::$View->title = 'Gestion des catégories';
        self::$View->Display('admin/categories');
    }

    public function users()
    {
        $userModel = new \Model\User();
        self::$View->users = $userModel->getAll();
        self::$View->title = 'Gestion des utilisateurs';
        self::$View->Display('admin/users');
    }

    public function editors()
    {
        $editorModel = new \Model\Editor();
        self::$View->editors = $editorModel->getAll();
        self::$View->title = 'Gestion des éditeurs';
        self::$View->Display('admin/editors');
    }

    public function games()
    {
        $gameModel = new \Model\Game();
        self::$View->games = $gameModel->getAllGames();
        self::$View->title = 'Gestion des jeux';
        self::$View->Display('admin/games');
    }

    // ... autres méthodes CRUD si nécessaire ...
}
