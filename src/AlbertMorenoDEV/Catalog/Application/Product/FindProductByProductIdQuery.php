<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\Product\ProductNotFoundException;
use AMD\Catalog\Domain\Model\Product\ProductRepository;
use AMD\Common\Application\Query;

class FindProductByProductIdQuery implements Query
{
    private $productId;

    public function __construct(string $productId)
    {
        $this->productId = ProductId::create($productId);
    }

    public function getProductId(): ProductId
    {
        return $this->productId;
    }
}