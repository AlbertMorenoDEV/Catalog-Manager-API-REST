<?php
namespace Tests\AppBundle\Fixtures\Entity;

use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;
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
        $family = new Family(FamilyId::create(), 'Family A');
        $this->setReference('family-a', $family);
        $manager->persist($family);

        $productA = $family->makeProduct(ProductId::create(), 'Product A');
        $this->setReference('product-a', $productA);
        $manager->persist($productA);

        $productB = $family->makeProduct(ProductId::create(), 'Product B');
        $this->setReference('product-b', $productB);
        $manager->persist($productB);

        $manager->flush();
    }
}