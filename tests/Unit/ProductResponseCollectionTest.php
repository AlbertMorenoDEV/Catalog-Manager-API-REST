<?php
namespace Tests\Unit;

use AMD\Catalog\Application\Product\InvalidProductResponseException;
use AMD\Catalog\Application\ProductResponseCollection;
use AMD\Catalog\Domain\Model\Family;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use PHPUnit_Framework_TestCase;

class ProductResponseCollectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException InvalidProductResponseException
     */
    public function itShouldThrowInvalidProductResponseException()
    {
        $productResponseCollection = new ProductResponseCollection();
        $productResponseCollection->add(new Family(FamilyId::create(), 'Family A'));
    }
}