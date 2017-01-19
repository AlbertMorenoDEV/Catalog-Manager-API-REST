<?php
namespace AMD\Catalog\Infrastructure\Persistence\Doctrine;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineFamilyId extends GuidType
{
    public function getName()
    {
        return 'FamilyId';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return FamilyId::create($value);
    }
}