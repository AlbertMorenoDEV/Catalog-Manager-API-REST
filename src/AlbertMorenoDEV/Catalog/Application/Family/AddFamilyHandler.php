<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Application\Family\AddFamily;
use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;

class AddFamilyHandler
{
    /** @var \AMD\Catalog\Domain\Model\Family\FamilyRepository */
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(AddFamily $request)
    {
        $family = new Family($request->getFamilyId(), $request->getName());

        $this->repository->add($family);
    }
}