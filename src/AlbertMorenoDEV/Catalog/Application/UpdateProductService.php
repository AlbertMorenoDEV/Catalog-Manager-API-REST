<?php
namespace AMD\Catalog\Application;

class UpdateProductService extends ProductService
{
    public function execute(UpdateProductRequest $request): UpdateProductResponse
    {
        $family = $this->findFamilyOrFail($request->getFamilyId());
        $product = $this->findProductOrFail($request->getId());

        $product->setDescription($request->getDescription());
        $product->setFamilyId($family->getId());
        $this->productRepository->update($product);

        return new UpdateProductResponse($product->getId(), $product->getDescription(), $family->getId());
    }
}