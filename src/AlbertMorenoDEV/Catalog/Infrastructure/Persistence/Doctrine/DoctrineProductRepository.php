<?php
namespace AMD\Catalog\Infrastructure\Persistence\Doctrine;

use AMD\Catalog\Domain\Model\Product;
use AMD\Catalog\Domain\Model\Product\ProductId;
use AMD\Catalog\Domain\Model\ProductRepository;
use Doctrine\ORM\EntityRepository;

class DoctrineProductRepository extends EntityRepository implements ProductRepository
{
    public function nextIdentity()
    {
        return ProductId::create();
    }

    public function add(Product $product)
    {
        $this->_em->persist($product);
        $this->_em->flush(); // ToDo: Must be moved to Application layer, but where exactly?
    }

    public function update(Product $product)
    {
        $this->_em->flush(); // ToDo: Must be moved to Application layer, but where exactly?
    }

    public function remove(Product $product)
    {
        $this->_em->remove($product);
        $this->_em->flush(); // ToDo: Must be moved to Application layer, but where exactly?
    }

    public function findById($productId)
    {
        return $this->find($productId);
    }
}
