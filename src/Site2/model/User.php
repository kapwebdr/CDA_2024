<?php
namespace Model;

class User extends Db
{
    public function getAll()
    {
        $sql = "SELECT u.*, GROUP_CONCAT(p.name) as profiles 
                FROM user u 
                LEFT JOIN user_profile up ON u.id = up.user_id 
                LEFT JOIN profile p ON up.profile_id = p.id 
                GROUP BY u.id";
        return $this->query($sql)->fetchAll();
    }

    public function getById($id)
    {
        $id = intval($id);
        $sql = "SELECT u.*, GROUP_CONCAT(p.name) as profiles 
                FROM user u 
                LEFT JOIN user_profile up ON u.id = up.user_id 
                LEFT JOIN profile p ON up.profile_id = p.id 
                WHERE u.id = :id 
                GROUP BY u.id";
        return $this->query($sql, [':id' => $id])->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
        $this->query($sql, [
            ':username' => $data['username'],
            ':email' => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
        ]);
        $userId = $this->lastInsertId();
        $this->addUserProfile($userId, 2); // Ajoute le profil "Utilisateur" par défaut
        return $userId;
    }

    public function update($id, $data)
    {
        $id = intval($id);
        $setClause = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $setClause[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE user SET " . implode(', ', $setClause) . " WHERE id = :id";
        return $this->query($sql, $params)->rowCount() > 0;
    }

    public function delete($id)
    {
        $id = intval($id);
        $sql = "DELETE FROM user WHERE id = :id";
        return $this->query($sql, [':id' => $id])->rowCount() > 0;
    }

    public function addUserProfile($userId, $profileId)
    {
        $sql = "INSERT INTO user_profile (user_id, profile_id) VALUES (:userId, :profileId)";
        $this->query($sql, [':userId' => $userId, ':profileId' => $profileId]);
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT u.*, GROUP_CONCAT(p.name) as profiles 
                FROM user u 
                LEFT JOIN user_profile up ON u.id = up.user_id 
                LEFT JOIN profile p ON up.profile_id = p.id 
                WHERE u.username = :username 
                GROUP BY u.id";
        return $this->query($sql, [':username' => $username])->fetch();
    }

    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    public function getUserProfiles($userId)
    {
        $sql = "SELECT p.* FROM profile p 
                JOIN user_profile up ON p.id = up.profile_id 
                WHERE up.user_id = :userId";
        return $this->query($sql, [':userId' => $userId])->fetchAll();
    }

    public function getUserPermissions($userId)
    {
        $sql = "SELECT DISTINCT p.* FROM permission p
                JOIN profile_permission pp ON p.id = pp.permission_id
                JOIN user_profile up ON pp.profile_id = up.profile_id
                WHERE up.user_id = :userId";
        return $this->query($sql, [':userId' => $userId])->fetchAll();
    }

    public function getUserById($id)
    {
        $sql = "SELECT u.*, GROUP_CONCAT(p.name) as profiles 
                FROM user u 
                LEFT JOIN user_profile up ON u.id = up.user_id 
                LEFT JOIN profile p ON up.profile_id = p.id 
                WHERE u.id = :id 
                GROUP BY u.id";
        return $this->query($sql, [':id' => $id])->fetch();
    }

    public function updateUser($id, $data)
    {
        $setClause = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $setClause[] = "$key = :$key";
            $params[":$key"] = $value;
        }

        $sql = "UPDATE user SET " . implode(', ', $setClause) . " WHERE id = :id";
        return $this->query($sql, $params)->rowCount() > 0;
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT u.*, GROUP_CONCAT(p.name) as profiles 
                FROM user u 
                LEFT JOIN user_profile up ON u.id = up.user_id 
                LEFT JOIN profile p ON up.profile_id = p.id 
                GROUP BY u.id
                ORDER BY u.username
                LIMIT :limit OFFSET :offset";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(':limit', intval($limit), \PDO::PARAM_INT);
        $stmt->bindValue(':offset', intval($offset), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getTotal()
    {
        $sql = "SELECT COUNT(*) FROM user";
        return intval($this->query($sql)->fetchColumn());
    }

}
