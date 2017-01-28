<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;

class UpdateProductService extends ProductService
{
    public function execute(UpdateProductRequest $request)
    {
        $family = $this->findFamilyOrFail(FamilyId::create($request->getFamilyId()));
        $product = $this->findProductOrFail(ProductId::create($request->getId()));

        $product->setDescription($request->getDescription());
        $product->setFamilyId($family->getFamilyId());
        $this->productRepository->update($product);
    }
}