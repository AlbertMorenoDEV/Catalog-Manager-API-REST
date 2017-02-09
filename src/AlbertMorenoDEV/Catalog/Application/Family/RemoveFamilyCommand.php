<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Common\Application\Command;

class RemoveFamilyCommand implements Command
{
    private $familyId;

    /**
     * RemoveFamilyCommand constructor.
     * @param string $familyId
     */
    public function __construct(string $familyId)
    {
        $this->familyId = FamilyId::create($familyId);
    }

    /**
     * @return FamilyId
     */
    public function getFamilyId(): FamilyId
    {
        return $this->familyId;
    }
}