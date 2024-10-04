<?php
namespace Controller;

class User extends Main
{
    public function getUsers($vars=[])
    {
        $users = new \Model\User();
        $_users = $users->getUsers();

        echo $this->twig->render('users.twig', [
            'title'=>'Liste des utilisateurs',
            'h1_title'=>'Liste des utilisateurs',
            'users'=>$_users,
            'menus'=>$this->menus]);
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
            $title = 'Utilisateur '.$user['firstname'];
            $h1_title = $user['firstname'].' '.$user['lastname'];
        }
        else {
            $title = 'Aucun utilisateur trouvé';
            $h1_title = 'Aucun utilisateur trouvé';
        }
        echo $this->twig->render('user.twig', [
            'title'=>$title,
            'h1_title'=>$h1_title,
            'user'=>$user,
            'menus'=>$this->menus]);
    }
}