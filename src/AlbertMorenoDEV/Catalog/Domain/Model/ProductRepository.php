<?php
namespace AMD\Catalog\Domain\Model;

use AMD\Catalog\Domain\Model\Product\ProductId;

interface ProductRepository
{
    public function nextIdentity(): ProductId;
    public function add(Product $product);
    public function update(Product $product);
    public function remove(Product $product);
    public function findAll();
    public function findByProductId(ProductId $productId);
}