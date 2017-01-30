<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Common\Application\Query;
use AMD\Common\Application\QueryHandler;

class FindAllFamiliesHandler implements QueryHandler
{
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(Query $query): array
    {
        return $this->repository->findAll();
    }
}