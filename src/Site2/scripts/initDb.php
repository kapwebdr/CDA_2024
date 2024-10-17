<?php
require_once __DIR__.'/../config/config.php';

$games = new \Model\Game();
$games->initGames();

echo "Base de données initialisée avec succès.\n";
