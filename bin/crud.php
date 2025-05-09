<?php

require_once __DIR__ . '/../vendor/autoload.php';

$entityManager = require_once __DIR__ . '/../config/doctrine.php';

use App\Entity\Product;

//CREATE
function createProduct($entityManager, $name, $price, $description = null)
{
    $product = new Product();
    $product->setName($name);
    $product->setPrice($price);
    $product->setDescription($description);

    $entityManager->persist($product);
    $entityManager->flush();

    echo "Created Product with ID " . $product->getId() . "\n";
    return $product;
}

//READ - find product by id
function findProduct($entityManager, $id)
{
    $product = $entityManager->find(Product::class, $id);

    if($product === null) {
        echo "Product with ID $id not found.\n";
        return null;
    }

    echo "Found Product: " . $product->getName() . ", Price: $" . $product->getPrice() . "\n";
    return $product;
}

//UPDATE - update product
function updateProduct($entityManager, $id, $newName, $newPrice)
{
    $product = $entityManager->find(Product::class, $id);

    if($product === null) {
        echo "Product with ID $id not found.\n";
        return null;
    }

    $product->setName($newName);
    $product->setPrice($newPrice);

    $entityManager->flush();

    echo "Updated Product with ID " . $product->getId() . "\n";
}

//DELETE - delete product
function deleteProduct($entityManager, $id)
{
    $productRepository = $entityManager->getRepository(Product::class);
    $products = $productRepository->findAll();

    echo "Listing all products:\n";
    foreach ($products as $product) {
        echo "- ID: " . $product->getId() . ", Name: " . $product->getName() .
        ", Price: $" . $product->getPrice() . "\n";
    }
}

?>