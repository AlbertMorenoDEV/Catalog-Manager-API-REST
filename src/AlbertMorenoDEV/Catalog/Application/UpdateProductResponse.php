<?php
namespace AMD\Catalog\Application;

class UpdateProductResponse
{
    /** @var int */
    private $id;

    /** @var string */
    private $description;

    /** @var int */
    private $familyId;

    public function __construct(int $id, string $description, int $familyId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->familyId = $familyId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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