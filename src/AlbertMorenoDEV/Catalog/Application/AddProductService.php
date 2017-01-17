<?php
namespace AMD\Catalog\Application;

class AddProductService extends ProductService
{
    public function execute(AddProductRequest $request): AddProductResponse
    {
        $family = $this->findFamilyOrFail($request->getFamilyId());

        $product = $family->makeProduct(null, $request->getDescription());

        $this->productRepository->add($product);

        return new AddProductResponse($product->getProductId(), $product->getDescription(), $product->getFamilyId());
    }
}