<?php
namespace AMD\Catalog\Application;

use AMD\Catalog\Domain\Model\Family;

class AddFamilyResponse
{
    /** @var string */
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function createFromFamily(Family $family): self
    {
        new self($family->getName());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}