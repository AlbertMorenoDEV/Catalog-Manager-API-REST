<?php
namespace AMD\Catalog\Application;

class AddProductRequest
{
    /** @var string */
    private $description;

    /** @var int */
    private $familyId;

    public function __construct(string $description, int $familyId)
    {
        $this->description = $description;
        $this->familyId = $familyId;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getFamilyId(): int
    {
        return $this->familyId;
    }
}