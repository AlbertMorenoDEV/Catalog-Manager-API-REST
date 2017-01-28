<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\FamilyId;

class RemoveFamilyCommand
{
    private $familyId;

    public function __construct(string $familyId)
    {
        $this->familyId = FamilyId::create($familyId);
    }

    public function getFamilyId(): FamilyId
    {
        return $this->familyId;
    }
}