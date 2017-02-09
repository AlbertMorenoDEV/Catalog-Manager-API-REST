<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Common\Application\Command;

class AddFamilyCommand implements Command
{
    private $familyId;
    private $name;

    /**
     * AddFamilyCommand constructor.
     * @param string $familyId
     * @param string $name
     */
    public function __construct(string $familyId, string $name)
    {
        $this->familyId = FamilyId::create($familyId);
        $this->name = $name;
    }

    /**
     * @return FamilyId
     */
    public function getFamilyId(): FamilyId
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