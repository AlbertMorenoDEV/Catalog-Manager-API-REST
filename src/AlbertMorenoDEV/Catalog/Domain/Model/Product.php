<?php
namespace AMD\Catalog\Domain\Model;
use AMD\Catalog\Domain\Model\Family\FamilyId;
use AMD\Catalog\Domain\Model\Product\ProductId;

/**
 * Product
 */
class Product
{
    const MIN_LENGTH = 2;
    const MAX_LENGTH = 255;
    const FORMAT = '/^[a-zA-Z0-9_ ]+$/';

    /**
     * @var ProductId
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var FamilyId
     */
    private $familyId;

    public function __construct(ProductId $id, $description, FamilyId $familyId)
    {
        $this->id = $id;
        $this->setDescription($description);
        $this->setFamilyId($familyId);
    }

    public function getProductId(): ProductId
    {
        return $this->id;
    }

    public function setDescription($description): self
    {
        $this->assertNotEmpty($description);
        $this->assertNotTooShort($description);
        $this->assertNotTooLong($description);
        $this->assertValidFormat($description);

        $this->description = $description;

        return $this;
    }

    private function assertNotEmpty($description)
    {
        if (empty($description)) {
            throw new InvalidProductDataException('Empty description');
        }
    }

    private function assertNotTooShort($description)
    {
        if (strlen($description) < self::MIN_LENGTH) {
            throw new InvalidProductDataException(sprintf('Description must be %d characters or more', self::MIN_LENGTH));
        }
    }

    private function assertNotTooLong($description)
    {
        if (strlen($description) > self::MAX_LENGTH) {
            throw new InvalidProductDataException(sprintf('Description must be %d characters or less', self::MAX_LENGTH));
        }
    }

    private function assertValidFormat($description)
    {
        if (preg_match(self::FORMAT, $description) !== 1) {
            throw new InvalidProductDataException('Invalid description format');
        }
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFamilyId(): FamilyId
    {
        return $this->familyId;
    }

    public function setFamilyId(FamilyId $familyId)
    {
        $this->familyId = $familyId;

        return $this;
    }
}

