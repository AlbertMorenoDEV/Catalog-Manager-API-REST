<?php
namespace AMD\Catalog\Domain\Model;

use AMD\Catalog\Domain\Model\Product\ProductId;

interface ProductRepository
{
    public function nextIdentity(): ProductId;
    public function add(Product $product): void;
    public function update(Product $product): void;
    public function remove(Product $product): void;
    public function findAll();
    public function findByProductId(ProductId $productId);
}