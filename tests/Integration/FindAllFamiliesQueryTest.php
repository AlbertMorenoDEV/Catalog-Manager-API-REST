<?php
namespace Tests\Integration;

use AMD\Catalog\Application\FindAllFamiliesQuery;
use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\FamilyRepository;
use AMD\Catalog\Infrastructure\Persistence\InMemory\InMemoryFamilyRepository;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindAllFamiliesQueryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FamilyRepository
     */
    private $repository;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->repository = new InMemoryFamilyRepository();
    }

    /**
     * @test
     * @group family
     */
    public function itShouldReturnAllFamilies()
    {
        $this->createFamilies(3);

        $query = new FindAllFamiliesQuery($this->repository);

        $this->assertCount(3, $query->execute());
    }

    private function createFamilies($int)
    {
        $faker = Factory::create();

        for ($i = 0; $i < $int; $i++) {
            $newFamily = new Family(FamilyId::create(), $faker->name);
            $this->repository->add($newFamily);
        }
    }
}
