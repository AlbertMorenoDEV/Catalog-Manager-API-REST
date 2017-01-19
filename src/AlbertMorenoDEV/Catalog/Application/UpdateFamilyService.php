<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\FamilyRepository;

class UpdateFamilyService
{
    /** @var FamilyRepository */
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(UpdateFamilyRequest $request): UpdateFamilyResponse
    {
        /** @var Family $family */
        $family = $this->repository->findById($request->getId());

        if (!$family) {
            throw new FamilyNotFoundException('No family found for id '.$request->getId());
        }

        $family->setName($request->getName());
        $this->repository->update($family);

        return new UpdateFamilyResponse($family->getFamilyId()->getId(), $family->getName());
    }
}