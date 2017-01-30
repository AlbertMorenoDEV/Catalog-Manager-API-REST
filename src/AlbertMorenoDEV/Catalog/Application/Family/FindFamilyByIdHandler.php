<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Common\Application\Query;
use AMD\Common\Application\QueryHandler;

class FindFamilyByIdHandler implements QueryHandler
{
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Query|FindFamilyByIdQuery $query
     * @return Family
     * @throws FamilyNotFoundException
     */
    public function handle(Query $query): Family
    {
        $family = $this->repository->findByFamilyId($query->getFamilyId());

        if (!$family) {
            throw new FamilyNotFoundException('No family found with id '.$query->getFamilyId()->getValue());
        }

        return $family;
    }
}