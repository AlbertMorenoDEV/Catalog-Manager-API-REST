<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\FamilyRepository;

class UpdateFamilyService
{
    /** @var FamilyRepository */
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateFamilyRequest $request)
    {
        /** @var Family $family */
        $family = $this->repository->findByFamilyId($request->getFamilyId());

        if (!$family) {
            throw new FamilyNotFoundException('No family found for id '.$request->getFamilyId());
        }

        $family->setName($request->getName());
        $this->repository->update($family);
    }
}