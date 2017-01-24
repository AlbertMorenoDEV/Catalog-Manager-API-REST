<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;

class AddProductService extends ProductService
{
    public function execute(AddProductRequest $request)
    {
        $family = $this->findFamilyOrFail($request->getFamilyId());

        $product = $family->makeProduct($request->getProductId(), $request->getDescription());

        $this->productRepository->add($product);
    }
}