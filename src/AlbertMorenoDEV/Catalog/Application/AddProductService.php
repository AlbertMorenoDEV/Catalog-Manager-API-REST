<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;

class AddProductService extends ProductService
{
    public function execute(AddProductRequest $request): AddProductResponse
    {
        $family = $this->findFamilyOrFail(FamilyId::create($request->getFamilyId()));

        $product = $family->makeProduct(ProductId::create($request->getId()), $request->getDescription());

        $this->productRepository->add($product);

        return new AddProductResponse($product->getProductId()->getValue(), $product->getDescription(), $product->getFamilyId()->getValue());
    }
}