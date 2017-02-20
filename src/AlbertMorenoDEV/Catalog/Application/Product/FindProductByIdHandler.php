<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Product\Product;
use AMD\Catalog\Domain\Model\Product\ProductNotFoundException;
use AMD\Catalog\Domain\Model\Product\ProductRepository;
use AMD\Common\Application\Query;
use AMD\Common\Application\QueryHandler;

class FindProductByIdHandler implements QueryHandler
{
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Query|FindProductByIdQuery $query
     * @return Product
     * @throws ProductNotFoundException
     */
    public function handle(Query $query): Product
    {
        $product = $this->repository->findByProductId($query->getProductId());

        if (!$product) {
            throw new ProductNotFoundException('No product found with id '.$query->getProductId()->getValue());
        }

        return $product;
    }
}