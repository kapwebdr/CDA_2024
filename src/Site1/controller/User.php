<?php
namespace Controller;

class User extends Main
{
    public function getUsers($vars=[])
    {
        $users = new \Model\User();
        $_users = $users->getUsers();
        
        $this->View->title      = 'Liste des utilisateurs';
        $this->View->h1_title   = 'Liste des utilisateurs';
        $this->View->users      = $_users;
        
        $this->View->Display('users');
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
            $this->View->title = 'Utilisateur '.$user['firstname'];
            $this->View->h1_title = $user['firstname'].' '.$user['lastname'];
        }
        else {
            $this->View->title = 'Aucun utilisateur trouvé';
            $this->View->h1_title = 'Aucun utilisateur trouvé';
        }

        $this->View->user = $user;
        $this->View->Display('user');
    }
}