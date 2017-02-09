<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Common\Application\Command;

class AddProductCommand implements Command
{
    private $productId;
    private $description;
    private $familyId;

    /**
     * AddProductCommand constructor.
     * @param string $productId
     * @param string $description
     * @param string $familyId
     */
    public function __construct(string $productId, string $description, string $familyId)
    {
        $this->productId = ProductId::create($productId);
        $this->description = $description;
        $this->familyId = FamilyId::create($familyId);
    }

    /**
     * @return ProductId
     */
    public function getProductId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return FamilyId
     */
    public function getFamilyId(): FamilyId
    {
        return $this->familyId;
    }
}