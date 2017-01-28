<?php
namespace AMD\Catalog\Domain\Model;

use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;

/**
 * Family
 */
class Family
{
    const MIN_LENGTH = 2;
    const MAX_LENGTH = 255;
    const FORMAT = '/^[a-zA-Z0-9_ .]+$/';

    /**
     * @var FamilyId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    public function __construct(FamilyId $id, $name)
    {
        $this->id = $id;
        $this->setName($name);
    }

    public function setName($name): self
    {
        $this->assertNotEmpty($name);
        $this->assertNotTooShort($name);
        $this->assertNotTooLong($name);
        $this->assertValidFormat($name);

        $this->name = $name;
        return $this;
    }

    public function makeProduct(ProductId $productId, string $description): Product
    {
        return new Product($productId, $description, $this->getFamilyId());
    }

    private function assertNotEmpty($name)
    {
        if (empty($name)) {
            throw new InvalidFamilyDataException('Empty name');
        }
    }

    private function assertNotTooShort($name)
    {
        if (strlen($name) < self::MIN_LENGTH) {
            throw new InvalidFamilyDataException(sprintf('Name must be %d characters or more', self::MIN_LENGTH));
        }
    }

    private function assertNotTooLong($name)
    {
        if (strlen($name) > self::MAX_LENGTH) {
            throw new InvalidFamilyDataException(sprintf('Name must be %d characters or less', self::MAX_LENGTH));
        }
    }

    private function assertValidFormat($name)
    {
        if (preg_match(self::FORMAT, $name) !== 1) {
            throw new InvalidFamilyDataException('Invalid name format: '.$name);
        }
    }

    public function getFamilyId(): FamilyId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

