<?php
namespace AMD\Catalog\Application\Product;

use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\Product\ProductNotFoundException;
use AMD\Catalog\Domain\Model\Product\ProductRepository;
use AMD\Common\Application\Command;
use AMD\Common\Application\CommandHandler;

class RemoveProductHandler implements CommandHandler
{
    /** @var \AMD\Catalog\Domain\Model\Product\ProductRepository */
    private $repository;

    /**
     * RemoveProductHandler constructor.
     * @param ProductRepository $repository
     */
    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Command|RemoveProductCommand $request
     * @throws \AMD\Catalog\Domain\Model\Product\ProductNotFoundException
     */
    public function handle(Command $request)
    {
        /** @var \AMD\Catalog\Domain\Model\Product\Product $product */
        $product = $this->repository->findByProductId(ProductId::create($request->getId()));

        if (!$product) {
            throw new ProductNotFoundException('No product found for id '.$request->getId());
        }

        $this->repository->remove($product);
    }
}