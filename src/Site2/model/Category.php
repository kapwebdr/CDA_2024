<?php
namespace Model;

class Category extends Db
{
    public function getAllCategories()
    {
        return $this->query("SELECT * FROM category ORDER BY name")->fetchAll();
    }
}
