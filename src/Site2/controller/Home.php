<?php
namespace Controller;

class Home extends Main
{
    function index($vars=[])
    {
        $gameModel = new \Model\Game();
        $featuredGames = $gameModel->getFeaturedGames(4); // Récupère 4 jeux en vedette

        self::$View->title = 'Accueil - Game Horizon';
        self::$View->h1_title = 'Game Horizon';
        self::$View->featured_games = $featuredGames;

        self::$View->Display('index');
    }
}
