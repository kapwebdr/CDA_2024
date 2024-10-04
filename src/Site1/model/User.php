<?php
namespace Model;

class User extends Db
{
    function getUsers()
    {
        return $this->query('Select * from user')->fetchAll();
    }

    function initUsers()
    {
        $json = file_get_contents(filename: '../db/users.json');
        $json = json_decode($json, associative: true);

        $sql = 'insert into user set 
        firstname=:firstname,
        lastname=:lastname,
        description=:description';
        
        foreach ($json as $user)
        {
            $prepare = self::$pdo->prepare($sql);
            $prepare->execute([
               ':firstname'=>$user['firstname'],
               ':lastname'=>$user['lastname'],
               ':description'=>$user['description'],
            ]);
        }
    }

    function getUser($id)
    {
        return $this->query('Select * from user where id=:id',[':id'=>$id])->fetch();
    }
}