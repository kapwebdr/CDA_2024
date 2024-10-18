<?php
namespace Model;

class Category extends Db
{
    public function getAll()
    {
        $sql = "SELECT * FROM category ORDER BY name";
        return $this->query($sql)->fetchAll();
    }

    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM category WHERE id = :id";
        return $this->query($sql, [':id' => $id])->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO category (name) VALUES (:name)";
        $this->query($sql, [':name' => $data['name']]);
        return $this->lastInsertId();
    }

    public function update($id, $data)
    {
        $id = intval($id);
        $sql = "UPDATE category SET name = :name WHERE id = :id";
        return $this->query($sql, [':id' => $id, ':name' => $data['name']])->rowCount() > 0;
    }

    public function delete($id)
    {
        $id = intval($id);
        $sql = "DELETE FROM category WHERE id = :id";
        return $this->query($sql, [':id' => $id])->rowCount() > 0;
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT * FROM category ORDER BY name LIMIT :limit OFFSET :offset";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':limit', intval($limit), \PDO::PARAM_INT);
        $stmt->bindValue(':offset', intval($offset), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotal()
    {
        $sql = "SELECT COUNT(*) FROM category";
        return intval($this->query($sql)->fetchColumn());
    }

    // ... autres méthodes si nécessaire ...
}
