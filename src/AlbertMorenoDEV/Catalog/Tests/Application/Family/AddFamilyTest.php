<?php
namespace AMD\Catalog\Tests\Application\Family;

use AMD\Catalog\Application\Family\AddFamily;
use AMD\Catalog\Domain\Model\Family\FamilyId;

class AddFamilyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_has_a_name()
    {
        $familyId = FamilyId::create();
        $name = 'Phones';
        $createNewProfile = new AddFamily($familyId, $name);

        self::assertSame($familyId->getValue(), $createNewProfile->getFamilyId()->getValue());
        self::assertSame($name, $createNewProfile->getName());
    }
}