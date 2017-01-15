<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Product;
use AMD\Catalog\Domain\Model\ProductRepository;

class AddProductService
{
    /** @var ProductRepository */
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(AddProductRequest $request): AddProductResponse
    {
        $product = new Product(null, $request->getDescription(), $request->getFamilyId());

        $this->repository->add($product);

        return new AddProductResponse($product->getId(), $product->getDescription(), $product->getFamilyId());
    }
}