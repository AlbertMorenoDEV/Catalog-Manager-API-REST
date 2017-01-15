<?php
namespace AMD\Catalog\Domain\Model;

interface ProductRepository
{
    public function add(Product $product);
    public function update(Product $product);
    public function remove(Product $product);
    public function findAll();
    public function findById($productId);
}