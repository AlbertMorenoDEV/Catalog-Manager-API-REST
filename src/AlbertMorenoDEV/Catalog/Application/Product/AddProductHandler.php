<?php
namespace AMD\Catalog\Application\Product;

class AddProductHandler extends ProductHandler
{
    public function execute(AddProductCommand $request)
    {
        $family = $this->findFamilyOrFail($request->getFamilyId());

        $product = $family->makeProduct($request->getProductId(), $request->getDescription());

        $this->productRepository->add($product);
    }
}