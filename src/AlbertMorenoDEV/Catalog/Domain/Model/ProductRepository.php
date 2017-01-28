<?php
namespace AMD\Catalog\Domain\Model;

use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;

interface ProductRepository
{
    /**
     * @return ProductId
     */
    public function nextIdentity(): ProductId;

    /**
     * @param Product $product
     * @return void
     */
    public function add(Product $product);

    /**
     * @param Product $product
     * @return void
     */
    public function update(Product $product);

    /**
     * @param Product $product
     * @return void
     */
    public function remove(Product $product);

    /**
     * @return array
     */
    public function findAll();

    /**
     * @param ProductId $productId
     * @return Product|null
     */
    public function findByProductId(ProductId $productId);
}