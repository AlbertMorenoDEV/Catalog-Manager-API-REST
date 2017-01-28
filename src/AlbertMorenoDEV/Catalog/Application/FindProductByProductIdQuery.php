<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\Product\ProductNotFoundException;
use AMD\Catalog\Domain\Model\Product\ProductRepository;

class FindProductByProductIdQuery
{
    private $repository;
    private $productId;

    public function __construct(ProductRepository $repository, ProductId $productId)
    {
        $this->repository = $repository;
        $this->productId = $productId;
    }

    public function execute(): Product
    {
        $product = $this->repository->findByProductId($this->productId);

        if (!$product) {
            throw new ProductNotFoundException('No product found for id '.$this->productId);
        }

        return $product;
    }
}