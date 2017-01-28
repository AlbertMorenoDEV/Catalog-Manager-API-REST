<?php
namespace AMD\Catalog\Infrastructure\Persistence\InMemory;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;

class InMemoryFamilyRepository implements FamilyRepository
{
    /** @var Family[] */
    private $items = [];

    public function nextIdentity(): FamilyId
    {
        return FamilyId::create();
    }

    public function add(Family $family)
    {
        $this->items[] = $family;
    }

    public function update(Family $family)
    {
        foreach ($this->items as $key => $item) {
            if ($item->getFamilyId()->isEqual($family->getFamilyId())) {
                $newFamily = new Family($family->getFamilyId(), $family->getName());
                $this->items[$key] = $newFamily;
                return;
            }
        }
    }

    public function remove(Family $family)
    {
        foreach ($this->items as $key => $item) {
            if ($item->getFamilyId()->isEqual($family->getFamilyId())) {
                unset($this->items[$key]);
                return;
            }
        }
    }

    public function findAll()
    {
        return $this->items;
    }

    public function findByFamilyId(FamilyId $familyId)
    {
        foreach ($this->items as $item) {
            if ($item->getFamilyId()->isEqual($familyId)) {
                return $item;
            }
        }

        return null;
    }
}