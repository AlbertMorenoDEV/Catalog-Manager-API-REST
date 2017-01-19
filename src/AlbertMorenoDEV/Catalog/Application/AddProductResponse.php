<?php
namespace AMD\Catalog\Application;

class AddProductResponse
{
    /** @var string */
    private $id;

    /** @var string */
    private $description;

    /** @var int */
    private $familyId;

    public function __construct(string $id, string $description, int $familyId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->familyId = $familyId;
    }

    /**
     * @return string
     */
    public function getId(): string
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
     * @return string
     */
    public function getFamilyId(): string
    {
        return $this->familyId;
    }
}