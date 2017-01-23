<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;

class FamilyResponse
{
    private $familyId;
    private $name;

    public function __construct(FamilyId $familyId, string $name)
    {
        $this->familyId = $familyId->getValue();
        $this->name = $name;
    }

    public static function createFromFamily(Family $family): self
    {
        return new self($family->getFamilyId(), $family->getName());
    }

    public function getFamilyId(): string
    {
        return $this->familyId;
    }

    public function getName(): string
    {
        return $this->name;
    }
}