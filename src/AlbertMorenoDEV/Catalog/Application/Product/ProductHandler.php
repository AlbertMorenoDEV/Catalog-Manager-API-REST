<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\Product\ProductNotFoundException;
use AMD\Catalog\Domain\Model\Product\ProductRepository;

abstract class ProductHandler
{
    protected $familyRepository;
    protected $productRepository;

    public function __construct(FamilyRepository $familyRepository, ProductRepository $productRepository)
    {
        $this->familyRepository = $familyRepository;
        $this->productRepository = $productRepository;
    }

    protected function findFamilyOrFail(FamilyId $familyId): Family
    {
        $family = $this->familyRepository->findByFamilyId($familyId);
        if ($family === null) {
            throw new FamilyNotFoundException('No family found for id '.$familyId->getValue());
        }

        return $family;
    }

    protected function findProductOrFail(ProductId $productId): Product
    {
        $product = $this->productRepository->findByProductId($productId);
        if ($product === null) {
            throw new ProductNotFoundException('No product found for id '.$productId->getValue());
        }

        return $product;
    }
}