<?php
namespace Tests\AppBundle\Fixtures\Entity;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Product;
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
        $family = new Family(null, 'Family A');
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