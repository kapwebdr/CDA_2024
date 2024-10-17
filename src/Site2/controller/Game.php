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

        $gameModel = new \Model\Game();
        $games = $gameModel->getGames($limit, $offset, $categoryId);
        $totalGames = $gameModel->getTotalGames($categoryId);
        $totalPages = ceil($totalGames / $limit);

        $categoryModel = new \Model\Category();
        $categories = $categoryModel->getAllCategories();

        self::$View->title = 'Liste des jeux';
        self::$View->h1_title = 'Catalogue de jeux';
        self::$View->games = $games;
        self::$View->categories = $categories;
        self::$View->currentPage = $page;
        self::$View->totalPages = $totalPages;
        self::$View->currentCategory = $categoryId;
        
        self::$View->Display('games');
    }
    
    public function getGame($vars=[])
    {
        $game = null;
        if(isset($vars['id']))
        {
            $id = intval($vars['id']);
            $games = new \Model\Game();
            $game = $games->getGame($id);
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
}
