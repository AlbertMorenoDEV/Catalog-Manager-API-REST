<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\FamilyRepository;
use AMD\Catalog\Domain\Model\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\ProductNotFoundException;
use AMD\Catalog\Domain\Model\ProductRepository;

abstract class ProductService
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
        $family = $this->familyRepository->findById($familyId);
        if ($family === null) {
            throw new FamilyNotFoundException('No family found for id '.$familyId->getValue());
        }

        return $family;
    }

    protected function findProductOrFail(ProductId $productId): Product
    {
        $product = $this->productRepository->findById($productId);
        if ($product === null) {
            throw new ProductNotFoundException('No product found for id '.$productId->getValue());
        }

        return $product;
    }
}