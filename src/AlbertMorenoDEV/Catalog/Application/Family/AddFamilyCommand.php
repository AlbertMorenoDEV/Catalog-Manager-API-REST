<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\FamilyId;

class AddFamilyCommand
{
    private $familyId;
    private $name;

    public function __construct(string $familyId, string $name)
    {
        $this->familyId = FamilyId::create($familyId);
        $this->name = $name;
    }

    public function getFamilyId(): FamilyId
    {
        return $this->familyId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}