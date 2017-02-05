<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;

class UpdateFamilyHandler
{
    /** @var FamilyRepository */
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateFamily $request)
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