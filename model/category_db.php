<?php

class CategoryDB
{

    public function getCategories()
    {
        $db = Database::getDB();
        $query = 'SELECT * FROM categories
                  ORDER BY categoryID';
        $statement = $db->prepare($query);
        $statement->execute();

        $categories = array();
        foreach ($statement as $row) {
            $categoryModel = new Category();
            $categoryModel->setID($row['categoryID']);
            $categoryModel->setName($row['categoryName']);

            $categories[] = $categoryModel;
        }
        return $categories;
    }

    public function getCategory($category_id)
    {
        $categoryModel = new Category();
        $db = Database::getDB();
        $query = 'SELECT * FROM categories
                  WHERE categoryID = :category_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();

        $categoryModel->setID($row['categoryID']);
        $categoryModel->setName($row['categoryName']);
        $category = $categoryModel;
        return $category;
    }
}

?>