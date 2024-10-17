<?php
namespace Model;

class Game extends Db
{
    
    function initGames()
    {
        $json = file_get_contents(filename: '../db/games.json');
        $json = json_decode($json, associative: true);

        $sql = 'INSERT INTO game SET 
        title=:title,
        description=:description,
        price=:price,
        image_url=:image_url';
        
        foreach ($json as $game)
        {
            $prepare = self::$pdo->prepare($sql);
            $prepare->execute([
               ':title'=>$game['title'],
               ':description'=>$game['description'],
               ':price'=>$game['price'],
               ':image_url'=>$game['image_url'],
            ]);
        }
    }

    function getGame($id)
    {
        return $this->query('SELECT * FROM game WHERE id=:id',[':id'=>$id])->fetch();
    }

    public function getFeaturedGames($limit = 4)
    {
        $sql = "SELECT * FROM game ORDER BY RAND() LIMIT :limit";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchGames($query)
    {
        $sql = "SELECT * FROM game WHERE title LIKE :query OR description LIKE :query";
        return $this->query($sql, [':query' => "%$query%"])->fetchAll();
    }

    public function getGames($limit = 12, $offset = 0, $categoryId = null)
    {
        $sql = "SELECT g.* FROM game g";
        $params = [];

        if ($categoryId) {
            $sql .= " JOIN game_category gc ON g.id = gc.game_id WHERE gc.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = (int)$limit;
        $params[':offset'] = (int)$offset;

        $stmt = $this->prepare($sql);
        
        // Bind les paramètres manuellement
        foreach ($params as $key => $value) {
            if ($key === ':limit' || $key === ':offset') {
                $stmt->bindValue($key, $value, \PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalGames($categoryId = null)
    {
        $sql = "SELECT COUNT(*) FROM game g";
        $params = [];

        if ($categoryId) {
            $sql .= " JOIN game_category gc ON g.id = gc.game_id WHERE gc.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }

        return $this->query($sql, $params)->fetchColumn();
    }
}
