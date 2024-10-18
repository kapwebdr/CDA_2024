<?php
namespace Controller;

class Game extends Main
{
    public function getGames($vars=[])
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12; // Nombre de jeux par page
        $offset = ($page - 1) * $limit;
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $editorId = isset($_GET['editor']) ? (int)$_GET['editor'] : null;
        $maxPrice = isset($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : null;
        $isOnline = isset($_GET['isOnline']) ? filter_var($_GET['isOnline'], FILTER_VALIDATE_BOOLEAN) : null;
        $isAvailable = isset($_GET['isAvailable']) ? filter_var($_GET['isAvailable'], FILTER_VALIDATE_BOOLEAN) : null;
        $isOnPromotion = isset($_GET['isOnPromotion']) ? filter_var($_GET['isOnPromotion'], FILTER_VALIDATE_BOOLEAN) : null;

        $gameModel = new \Model\Game();
        $games = $gameModel->getFilteredGames($limit, $offset, $categoryId, $editorId, $maxPrice, $isOnline, $isAvailable, $isOnPromotion);
        $totalGames = $gameModel->getTotalFilteredGames($categoryId, $editorId, $maxPrice, $isOnline, $isAvailable, $isOnPromotion);
        $totalPages = ceil($totalGames / $limit);

        $categoryModel = new \Model\Category();
        $categories = $categoryModel->getAll();

        $editorModel = new \Model\Editor();
        $editors = $editorModel->getAll();

        self::$View->title = 'Liste des jeux';
        self::$View->h1_title = 'Catalogue de jeux';
        self::$View->games = $games;
        self::$View->categories = $categories;
        self::$View->editors = $editors;
        self::$View->currentPage = $page;
        self::$View->totalPages = $totalPages;
        self::$View->currentCategory = $categoryId;
        self::$View->currentEditor = $editorId;
        self::$View->maxPrice = $maxPrice ?? 100; // Valeur par défaut pour le slider de prix
        self::$View->isOnline = $isOnline;
        self::$View->isAvailable = $isAvailable;
        self::$View->isOnPromotion = $isOnPromotion;
        
        if (isset($_GET['ajax'])) {
            self::$View->Display('games_list');
        } else {
            self::$View->Display('games');
        }
    }
    
    public function getGame($vars=[])
    {
        $game = null;
        if(isset($vars['id']))
        {
            $id = intval($vars['id']);
            $gameModel = new \Model\Game();
            $game = $gameModel->getGame($id);
            
            if ($game) {
                $editorModel = new \Model\Editor();
                $game['editor'] = $editorModel->getEditorById($game['editor_id']);
            }
        }
        if(!is_null($game) && isset($game['id']))
        {
            self::$View->title = $game['title'];
            self::$View->h1_title = $game['title'];
        }
        else {
            self::$View->title = 'Jeu non trouvé';
            self::$View->h1_title = 'Jeu non trouvé';
        }

        self::$View->game = $game;
        self::$View->Display('game');
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        $gameModel = new \Model\Game();
        $games = $gameModel->searchGames($query);

        self::$View->title = 'Résultats de recherche';
        self::$View->games = $games;
        self::$View->query = $query;
        self::$View->Display('search_results');
    }

    public function filterGames()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 12;
        $offset = ($page - 1) * $limit;
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : null;
        $editorId = isset($_GET['editor']) ? (int)$_GET['editor'] : null;
        $maxPrice = isset($_GET['maxPrice']) ? (float)$_GET['maxPrice'] : null;

        $gameModel = new \Model\Game();
        $games = $gameModel->getFilteredGames($limit, $offset, $categoryId, $editorId, $maxPrice);
        $totalGames = $gameModel->getTotalFilteredGames($categoryId, $editorId, $maxPrice);
        $totalPages = ceil($totalGames / $limit);

        self::$View->games = $games;
        self::$View->currentPage = $page;
        self::$View->totalPages = $totalPages;
        self::$View->Display('games_list');
    }
}
