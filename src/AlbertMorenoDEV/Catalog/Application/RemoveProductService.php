<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Product;
use AMD\Catalog\Domain\Model\ProductNotFoundException;
use AMD\Catalog\Domain\Model\ProductRepository;

class RemoveProductService
{
    /** @var ProductRepository */
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(RemoveProductRequest $request): RemoveProductResponse
    {
        /** @var Product $product */
        $product = $this->repository->findById($request->getId());

        if (!$product) {
            throw new ProductNotFoundException('No product found for id '.$request->getId());
        }

        $response = new RemoveProductResponse($product->getProductId(), $product->getDescription(), $product->getFamilyId());

        $this->repository->remove($product);

        return $response;
    }
}