<?php
namespace AlbertMorenoDEV\CatalogBundle\Tests\Fixtures\Entity;

use AlbertMorenoDEV\CatalogBundle\Entity\Family;
use AlbertMorenoDEV\CatalogBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData extends AbstractFixture implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $family = new Family();
        $family->setName('Family A');
        $this->setReference('family-a', $family);
        $manager->persist($family);

        $product = new Product();
        $product->setDescription('Product A');
        $product->setFamily($family);
        $this->setReference('product-a', $product);
        $manager->persist($product);

        $product = new Product();
        $product->setDescription('Product B');
        $product->setFamily($family);
        $this->setReference('product-b', $product);
        $manager->persist($product);

        $manager->flush();
    }
}