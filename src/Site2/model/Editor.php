<?php
namespace Model;

class Editor extends Db
{
    public function getAll()
    {
        $sql = "SELECT e.*, u.username as user_username 
                FROM editor e
                LEFT JOIN user u ON e.user_id = u.id
                ORDER BY e.name";
        return $this->query($sql)->fetchAll();
    }

    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT e.*, u.username as user_username 
                FROM editor e
                LEFT JOIN user u ON e.user_id = u.id
                WHERE e.id = :id";
        return $this->query($sql, [':id' => $id])->fetch();
    }

    public function getEditorById($id)
    {
        return $this->getById($id);
    }

    public function getEditorByUserId($userId)
    {
        $sql = "SELECT e.*, u.username as user_username 
                FROM editor e
                LEFT JOIN user u ON e.user_id = u.id
                WHERE e.user_id = :userId";
        return $this->query($sql, [':userId' => $userId])->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO editor (name, user_id) VALUES (:name, :user_id)";
        $this->query($sql, [':name' => $data['name'], ':user_id' => $data['user_id']]);
        return $this->lastInsertId();
    }

    public function update($id, $data)
    {
        $id = intval($id);
        $sql = "UPDATE editor SET name = :name, user_id = :user_id WHERE id = :id";
        return $this->query($sql, [
            ':id' => $id,
            ':name' => $data['name'],
            ':user_id' => $data['user_id']
        ])->rowCount() > 0;
    }

    public function delete($id)
    {
        $id = intval($id);
        $sql = "DELETE FROM editor WHERE id = :id";
        return $this->query($sql, [':id' => $id])->rowCount() > 0;
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT e.*, u.username as user_username 
                FROM editor e
                LEFT JOIN user u ON e.user_id = u.id
                ORDER BY e.name
                LIMIT :limit OFFSET :offset";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':limit', intval($limit), \PDO::PARAM_INT);
        $stmt->bindValue(':offset', intval($offset), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotal()
    {
        $sql = "SELECT COUNT(*) FROM editor";
        return intval($this->query($sql)->fetchColumn());
    }

    // ... autres méthodes existantes ...
}
