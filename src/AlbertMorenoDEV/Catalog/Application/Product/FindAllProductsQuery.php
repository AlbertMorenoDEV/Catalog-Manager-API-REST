<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Product\ProductRepository;

class FindAllProductsQuery
{
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(): array
    {
        return $this->repository->findAll();
    }
}