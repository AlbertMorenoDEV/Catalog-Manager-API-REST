<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Application\Product\ProductService;

class AddProductService extends ProductService
{
    public function execute(AddProductRequest $request)
    {
        $family = $this->findFamilyOrFail($request->getFamilyId());

        $product = $family->makeProduct($request->getProductId(), $request->getDescription());

        $this->productRepository->add($product);
    }
}