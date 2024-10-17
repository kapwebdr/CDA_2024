<?php
namespace Model;

class Db 
{
    static protected $pdo = null;

    public function __construct()
    {
        try {
            if (is_null(self::$pdo)) {
                self::$pdo = new \PDO(
                    "mysql:host=" . DB_HOSTNAME . ";dbname=" . DB_NAME . ";charset=utf8",
                    DB_USER,
                    DB_PASSWORD
                );
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            }
        } catch (\PDOException $e) {
            \Controller\Error::PdoException($e);
        }
    }

    public function query($sql, $params = [])
    {
        try {
            $prepare = self::$pdo->prepare($sql);
            $prepare->execute($params);
            return $prepare;
        } catch (\PDOException $e) {
            \Controller\Error::PdoException($e);
        }
    }

    public function lastInsertId()
    {
        return self::$pdo->lastInsertId();
    }

    public function prepare($sql)
    {
        return self::$pdo->prepare($sql);
    }
}
