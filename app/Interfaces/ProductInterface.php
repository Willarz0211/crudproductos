<?php

namespace App\Interfaces;

interface ProductInterface
{
    public function getAllProducts();
    public function getProductById($id);
    public function createProduct($data);
    public function updateProduct($data, $id);
    public function deleteProduct($id);
    public function getImagesByProduct($id);
}