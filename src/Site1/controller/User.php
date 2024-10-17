<?php
namespace Controller;

class User extends Main
{
    public function getUsers($vars=[])
    {
        $users = new \Model\User();
        $_users = $users->getUsers();
        
        Main::$View->title      = 'Liste des utilisateurs';
        Main::$View->h1_title   = 'Liste des utilisateurs';
        Main::$View->users      = $_users;
        
        Main::$View->Display('users');
    }
    
    public function getUser($vars=[])
    {
        $user = null;
        if(isset($vars['id']))
        {
            $id     = intval($vars['id']);
            $users  = new \Model\User();
            $user   = $users->getUser($id);
        }
        if(!is_null($user) && isset($user['id']))
        {
            Main::$View->title = 'Utilisateur '.$user['firstname'];
            Main::$View->h1_title = $user['firstname'].' '.$user['lastname'];
        }
        else {
            Main::$View->title = 'Aucun utilisateur trouvé';
            Main::$View->h1_title = 'Aucun utilisateur trouvé';
        }

        Main::$View->user = $user;
        Main::$View->Display('user');
    }
}