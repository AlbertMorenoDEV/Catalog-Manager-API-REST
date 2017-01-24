<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product;

class ProductResponseCollection
{
    private $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param Product[] $products
     * @return ProductResponseCollection
     */
    public static function createFromProductArray(array $products): self
    {
        $new_collection = new self();

        if (count($products)) {
            foreach ($products as $product) {
                $new_collection->add($product);
            }
        }

        return $new_collection;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    private function add(Product $product)
    {
        $this->items[] = ProductResponse::createFromProduct($product);
    }
}