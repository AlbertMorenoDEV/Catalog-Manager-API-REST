<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\FamilyRepository;

class AddFamilyService
{
    /** @var FamilyRepository */
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(AddFamilyRequest $request): AddFamilyResponse
    {
        $family = new Family(null, $request->getName());

        $this->repository->add($family);

        return new AddFamilyResponse($family->getName());
    }
}