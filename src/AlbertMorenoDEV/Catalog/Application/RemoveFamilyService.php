<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\FamilyRepository;

class RemoveFamilyService
{
    /** @var FamilyRepository */
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(RemoveFamilyRequest $request)
    {
        /** @var \AMD\Catalog\Domain\Model\Family\Family $family */
        $family = $this->repository->findByFamilyId($request->getFamilyId());

        if (!$family) {
            throw new FamilyNotFoundException('No family found for id '.$request->getFamilyId());
        }

        $this->repository->remove($family);
    }
}