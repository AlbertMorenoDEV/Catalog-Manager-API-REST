<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Common\Application\Query;

class FindFamilyByIdQuery implements Query
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