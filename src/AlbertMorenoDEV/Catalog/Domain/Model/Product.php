<?php
namespace AMD\Catalog\Domain\Model;

/**
 * Product
 */
class Product
{
    const MIN_LENGTH = 2;
    const MAX_LENGTH = 255;
    const FORMAT = '/^[a-zA-Z0-9_ ]+$/';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $familyId;

    /**
     * @var Family
     */
//    private $family;

    public function __construct($id, $description, $familyId)
    {
        $this->id = $id;
        $this->setDescription($description);
        $this->setFamilyId($familyId);
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set description
     *
     * @param $description
     *
     * @return Product
     */
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

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set family id
     *
     * @param $familyId
     *
     * @return Product
     */
    public function setFamilyId($familyId): self
    {
        $this->assertValidFamilyId($familyId);

        $this->familyId = $familyId;

        return $this;
    }

    private function assertValidFamilyId($familyId)
    {
        // ToDo: validate if faimly exists
    }

    /**
     * @return int
     */
    public function getFamilyId(): int
    {
        return $this->familyId;
    }

//    /**
//     * @return Family
//     */
//    public function getFamily(): Family
//    {
//        return $this->family;
//    }
//
//    /**
//     * @param Family $family
//     */
//    public function setFamily(Family $family)
//    {
//        $this->family = $family;
//    }
}

