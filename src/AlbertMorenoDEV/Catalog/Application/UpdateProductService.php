<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Product;
use AMD\Catalog\Domain\Model\ProductNotFoundException;
use AMD\Catalog\Domain\Model\ProductRepository;

class UpdateProductService
{
    /** @var ProductRepository */
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateProductRequest $request): UpdateProductResponse
    {
        // ToDo: Maybe a app service shouldn't know about Product directly?
        /** @var Product $product */
        $product = $this->repository->findById($request->getId());

        if (!$product) {
            throw new ProductNotFoundException('No product found for id '.$request->getId());
        }

        $product->setDescription($request->getDescription());
        $product->setFamilyId($request->getFamilyId());
        $this->repository->update($product);

        return new UpdateProductResponse($product->getId(), $product->getDescription(), $product->getFamilyId());
    }
}