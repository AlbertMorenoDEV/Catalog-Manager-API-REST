<?php
namespace AMD\Catalog\Domain\Model;

use AMD\Catalog\Application\AddFamilyRequest;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Family
 */
class Family
{
    const MIN_LENGTH = 5;
    const MAX_LENGTH = 10;
    const FORMAT = '/^[a-zA-Z0-9_ ]+$/';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ArrayCollection
     */
    private $products;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->setName($name);
        $this->products = new ArrayCollection();
    }

    public static function createFromAddFamilyRequest(AddFamilyRequest $request): self
    {
        return new self(null, $request->getName());
    }

    private function setName($name)
    {
        $this->assertNotEmpty($name);
        $this->assertNotTooShort($name);
        $this->assertNotTooLong($name);
        $this->assertValidFormat($name);

        $this->name = $name;
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
            throw new InvalidFamilyDataException('Invalid name format');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

