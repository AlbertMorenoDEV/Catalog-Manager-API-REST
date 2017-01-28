<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;

class ProductResponse
{
    private $productId;
    private $description;

    public function __construct(ProductId $productId, string $description)
    {
        $this->productId = $productId->getValue();
        $this->description = $description;
    }

    public static function createFromProduct(Product $product): self
    {
        return new self($product->getProductId(), $product->getDescription());
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}