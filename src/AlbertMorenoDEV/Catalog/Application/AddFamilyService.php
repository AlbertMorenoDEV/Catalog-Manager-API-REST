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
        $family = Family::createFromAddFamilyRequest($request);

        $this->repository->add($family);

        return AddFamilyResponse::createFromFamily($family);
    }
}