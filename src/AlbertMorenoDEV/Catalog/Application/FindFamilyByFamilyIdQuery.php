<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\FamilyRepository;

class FindFamilyByFamilyIdQuery
{
    private $repository;
    private $familyId;

    public function __construct(FamilyRepository $repository, FamilyId $familyId)
    {
        $this->repository = $repository;
        $this->familyId = $familyId;
    }

    public function execute(): Family
    {
        $family = $this->repository->findByFamilyId($this->familyId);

        if (!$family) {
            throw new FamilyNotFoundException('No family found for id '.$this->familyId);
        }

        return $family;
    }
}