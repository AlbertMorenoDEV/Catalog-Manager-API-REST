<?php
namespace Tests\Integration\Catalog\Application\Family;

use AMD\Catalog\Application\Family\FindAllFamiliesHandler;
use AMD\Catalog\Application\Family\FindAllFamiliesQuery;
use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Catalog\Infrastructure\Persistence\InMemory\InMemoryFamilyRepository;
use Faker\Factory;

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

        $query = new FindAllFamiliesQuery();
        $handler = new FindAllFamiliesHandler($this->repository);

        $results = $handler->handle($query);

        $this->assertCount(3, $results);
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
