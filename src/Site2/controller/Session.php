<?php
namespace Controller;

class Session
{
  /**
   * Returns true if there is a cookie with this name.
   *
   * @param string $name
   * @return bool
   */
  static public function Exists($name)
  {
    return isset($_SESSION[$name]);
  }

  /**
   * Returns true if there no cookie with this name or it's empty, or 0,
   * or a few other things. Check http://php.net/empty for a full list.
   *
   * @param string $name
   * @return bool
   */
  static public function IsEmpty($name)
  {
    return empty($_SESSION[$name]);
  }

  /**
   * Get the value of the given cookie. If the cookie does not exist the value
   * of $default will be returned.
   *
   * @param string $name
   * @param string $default
   * @return mixed
   */
  static public function Get($name, $default = '')
  {
    return (isset($_SESSION[$name]) ? $_SESSION[$name] : $default);
  }
  // var_dump(Session::Get('user',null));
  // if(is_null(Session::Get('user',null)))

  /**
   * Set a cookie. Silently does nothing if headers have already been sent.
   *
   * @param string $name
   * @param string $value
   * @param mixed $expiry
   * @param string $path
   * @param string $domain
   * @return bool
   */
  static public function Set($name, $value)
  {
        $_SESSION[$name] = $value;
  }
  // Session::Set('user',$user);

  /**
   * Delete a cookie.
   *
   * @param string $name
   * @param string $path
   * @param string $domain
   * @param bool $remove_from_global Set to true to remove this cookie from this request.
   * @return bool
   */
  static public function Delete($name, $path = '/', $domain = false, $remove_from_global = true)
  {
    unset($_SESSION[$name]);
  }

  static public function Destroy()
  {
    session_destroy();
  }

  static public function Start()
  {
    session_start();
  }

  static public function Id($id=null)
  {
    return session_id($id);
  }

  static public function Save()
  {
    /*
    Sauvegarde de la session en Base de données.
    */
  }


  static public function Restore()
  {
    /*
    Récupére de la session en Base de données.
    et met les valeurs dans $_SESSION 
    */
  }
}

/*
use /Conrtoller/Session

var_dump(Session::Get('user'));
Session::Set('user',$user);

*/

?>