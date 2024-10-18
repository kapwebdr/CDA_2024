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

    public function getGame($id)
    {
        $sql = "SELECT g.*, e.name as editor_name FROM game g
                LEFT JOIN editor e ON g.editor_id = e.id
                WHERE g.id = :id";
        return $this->query($sql, [':id' => $id])->fetch();
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

    public function getGames($limit = 12, $offset = 0, $categoryId = null, $editorId = null)
    {
        $sql = "SELECT DISTINCT g.*, e.name as editor_name FROM game g
                LEFT JOIN editor e ON g.editor_id = e.id";
        $params = [];

        if ($categoryId) {
            $sql .= " JOIN game_category gc ON g.id = gc.game_id";
        }

        $where = [];
        if ($categoryId) {
            $where[] = "gc.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($editorId) {
            $where[] = "g.editor_id = :editorId";
            $params[':editorId'] = $editorId;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY g.release_date DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalGames($categoryId = null, $editorId = null)
    {
        $sql = "SELECT COUNT(DISTINCT g.id) FROM game g";
        $params = [];

        if ($categoryId) {
            $sql .= " JOIN game_category gc ON g.id = gc.game_id";
        }

        $where = [];
        if ($categoryId) {
            $where[] = "gc.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($editorId) {
            $where[] = "g.editor_id = :editorId";
            $params[':editorId'] = $editorId;
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        return $this->query($sql, $params)->fetchColumn();
    }

    public function getFilteredGames($limit = 12, $offset = 0, $categoryId = null, $editorId = null, $maxPrice = null, $isOnline = null, $isAvailable = null, $isOnPromotion = null)
    {
        $sql = "SELECT DISTINCT g.*, e.name as editor_name FROM game g
                LEFT JOIN editor e ON g.editor_id = e.id";
        $params = [];

        if ($categoryId) {
            $sql .= " JOIN game_category gc ON g.id = gc.game_id";
        }

        $where = [];
        if ($categoryId) {
            $where[] = "gc.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($editorId) {
            $where[] = "g.editor_id = :editorId";
            $params[':editorId'] = $editorId;
        }
        if ($maxPrice !== null) {
            $where[] = "CASE WHEN g.is_on_promotion = 1 THEN g.promotion_price ELSE g.price END <= :maxPrice";
            $params[':maxPrice'] = $maxPrice;
        }
        if ($isOnline === true) {
            $where[] = "g.is_online = 1";
        }
        if ($isAvailable === true) {
            $where[] = "g.availability_date <= CURDATE()";
        }
        if ($isOnPromotion === true) {
            $where[] = "g.is_on_promotion = 1";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $sql .= " ORDER BY g.release_date DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, \PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotalFilteredGames($categoryId = null, $editorId = null, $maxPrice = null, $isOnline = null, $isAvailable = null, $isOnPromotion = null)
    {
        $sql = "SELECT COUNT(DISTINCT g.id) FROM game g";
        $params = [];

        if ($categoryId) {
            $sql .= " JOIN game_category gc ON g.id = gc.game_id";
        }

        $where = [];
        if ($categoryId) {
            $where[] = "gc.category_id = :categoryId";
            $params[':categoryId'] = $categoryId;
        }
        if ($editorId) {
            $where[] = "g.editor_id = :editorId";
            $params[':editorId'] = $editorId;
        }
        if ($maxPrice !== null) {
            $where[] = "CASE WHEN g.is_on_promotion = 1 THEN g.promotion_price ELSE g.price END <= :maxPrice";
            $params[':maxPrice'] = $maxPrice;
        }
        if ($isOnline === true) {
            $where[] = "g.is_online = 1";
        }
        if ($isAvailable === true) {
            $where[] = "g.availability_date <= CURDATE()";
        }
        if ($isOnPromotion === true) {
            $where[] = "g.is_on_promotion = 1";
        }

        if (!empty($where)) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }

        $stmt = $this->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getAllGames()
    {
        $sql = "SELECT g.*, e.name as editor_name 
                FROM game g
                LEFT JOIN editor e ON g.editor_id = e.id
                ORDER BY g.release_date DESC";
        return $this->query($sql)->fetchAll();
    }

    public function getAll()
    {
        $sql = "SELECT g.*, e.name as editor_name 
                FROM game g
                LEFT JOIN editor e ON g.editor_id = e.id
                ORDER BY g.release_date DESC";
        return $this->query($sql)->fetchAll();
    }

    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT g.*, e.name as editor_name 
                FROM game g
                LEFT JOIN editor e ON g.editor_id = e.id
                WHERE g.id = :id";
        return $this->query($sql, [':id' => $id])->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO game (title, description, price, image_url, release_date, editor_id, is_online, availability_date, is_on_promotion, promotion_price) 
                VALUES (:title, :description, :price, :image_url, :release_date, :editor_id, :is_online, :availability_date, :is_on_promotion, :promotion_price)";
        $this->query($sql, $data);
        return $this->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE game SET 
                title = :title, 
                description = :description, 
                price = :price, 
                release_date = :release_date, 
                editor_id = :editor_id, 
                is_online = :is_online, 
                availability_date = :availability_date, 
                is_on_promotion = :is_on_promotion, 
                promotion_price = :promotion_price";
        
        if (isset($data['image_url'])) {
            $sql .= ", image_url = :image_url";
        }
        
        $sql .= " WHERE id = :id";
        
        $data[':id'] = $id;
        return $this->query($sql, $data)->rowCount() > 0;
    }

    public function delete($id)
    {
        $id = intval($id);
        $sql = "DELETE FROM game WHERE id = :id";
        return $this->query($sql, [':id' => $id])->rowCount() > 0;
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT g.*, e.name as editor_name 
                FROM game g
                LEFT JOIN editor e ON g.editor_id = e.id
                ORDER BY g.release_date DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':limit', intval($limit), \PDO::PARAM_INT);
        $stmt->bindValue(':offset', intval($offset), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotal()
    {
        $sql = "SELECT COUNT(*) FROM game";
        return intval($this->query($sql)->fetchColumn());
    }
}
