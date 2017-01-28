<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;

class FamilyResponseCollection
{
    private $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param Family[] $families
     * @return \self
     */
    public static function createFromFamilyArray(array $families): self
    {
        $new_collection = new self();

        if (count($families)) {
            foreach ($families as $family) {
                $new_collection->add($family);
            }
        }

        return $new_collection;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    private function add(Family $family)
    {
        $this->items[] = FamilyResponse::createFromFamily($family);
    }
}