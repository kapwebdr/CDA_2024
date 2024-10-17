<?php
namespace Model;

class User extends Db
{
    public function createUser($username, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
        $this->query($sql, [':username' => $username, ':email' => $email, ':password' => $hashedPassword]);
        return $this->lastInsertId();
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM user WHERE username = :username";
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
        $sql = "SELECT DISTINCT perm.* FROM permission perm 
                JOIN profile_permission pp ON perm.id = pp.permission_id 
                JOIN user_profile up ON pp.profile_id = up.profile_id 
                WHERE up.user_id = :userId";
        return $this->query($sql, [':userId' => $userId])->fetchAll();
    }

    public function addUserProfile($userId, $profileId)
    {
        $sql = "INSERT INTO user_profile (user_id, profile_id) VALUES (:userId, :profileId)";
        $this->query($sql, [':userId' => $userId, ':profileId' => $profileId]);
    }
}
