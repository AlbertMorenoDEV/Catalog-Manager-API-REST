<?php
namespace AMD\Catalog\Infrastructure\Persistence\Doctrine;

use AMD\Catalog\Domain\Model\Product\ProductId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineProductId extends GuidType
{
    public function getName()
    {
        return 'ProductId';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ProductId::create($value);
    }
}