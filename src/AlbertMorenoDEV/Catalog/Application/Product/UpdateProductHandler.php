<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Common\Application\Command;
use AMD\Common\Application\CommandHandler;

class UpdateProductHandler extends ProductHandler implements CommandHandler
{
    /**
     * @param Command|UpdateProductCommand $request
     */
    public function handle(Command $request)
    {
        $family = $this->findFamilyOrFail(FamilyId::create($request->getFamilyId()));
        $product = $this->findProductOrFail(ProductId::create($request->getId()));

        $product->setDescription($request->getDescription());
        $product->setFamilyId($family->getFamilyId());
        $this->productRepository->update($product);
    }
}