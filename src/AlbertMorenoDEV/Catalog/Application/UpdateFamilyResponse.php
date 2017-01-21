<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family\FamilyId;

class UpdateFamilyResponse
{
    private $familyId;
    private $name;

    public function __construct(FamilyId $familyId, string $name)
    {
        $this->familyId = $familyId->getValue();
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getFamilyId(): string
    {
        return $this->familyId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}