<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\Product\ProductNotFoundException;
use AMD\Catalog\Domain\Model\ProductRepository;

class RemoveProductService
{
    /** @var ProductRepository */
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(RemoveProductRequest $request)
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->repository->findByProductId(ProductId::create($request->getId()));

        if (!$product) {
            throw new ProductNotFoundException('No product found for id '.$request->getId());
        }

        $this->repository->remove($product);
    }
}