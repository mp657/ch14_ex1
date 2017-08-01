<?php

class ProductDB
{
    public function getProductsByCategory($category_id)
    {
        $db = Database::getDB();

        $categoryDB = new CategoryDB();
        $category = $categoryDB->getCategory($category_id);

        $query = 'SELECT * FROM products
                  WHERE products.categoryID = :category_id
                  ORDER BY productID';
        $statement = $db->prepare($query);
        $statement->bindValue(":category_id", $category_id);
        $statement->execute();
        $rows = $statement->fetchAll();
        $statement->closeCursor();

        foreach ($rows as $row) {
            $productModel = new Product();
            $productModel->setCategory($category);
            $productModel->setCode($row['productCode']);
            $productModel->setName($row['productName']);
            $productModel->setPrice($row['listPrice']);
            $productModel->setId($row['productID']);
            $products[] = $productModel;
        }
        return $products;
    }

    public function getProduct($product_id)
    {
        $db = Database::getDB();
        $query = 'SELECT * FROM products
                  WHERE productID = :product_id';
        $statement = $db->prepare($query);
        $statement->bindValue(":product_id", $product_id);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();

        $categoryDB = new CategoryDB();
        $category = $categoryDB->getCategory($row['categoryID']);

        $productModel = new Product();
        $productModel->setCategory($category);
        $productModel->setCode($row['productCode']);
        $productModel->setName($row['productName']);
        $productModel->setPrice($row['listPrice']);
        $productModel->setId($row['productID']);

        return $productModel;
    }

    public function deleteProduct($product_id)
    {
        $db = Database::getDB();
        $query = 'DELETE FROM products
                  WHERE productID = :product_id';
        $statement = $db->prepare($query);
        $statement->bindValue(':product_id', $product_id);
        $statement->execute();
        $statement->closeCursor();
    }

    public function addProduct($product)
    {
        $db = Database::getDB();

        $category_id = $product->getCategory()->getID();
        $code = $product->getCode();
        $name = $product->getName();
        $price = $product->getPrice();

        $query = 'INSERT INTO products
                     (categoryID, productCode, productName, listPrice)
                  VALUES
                     (:category_id, :code, :name, :price)';
        $statement = $db->prepare($query);
        $statement->bindValue(':category_id', $category_id);
        $statement->bindValue(':code', $code);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':price', $price);
        $statement->execute();
        $statement->closeCursor();
    }
}

?>