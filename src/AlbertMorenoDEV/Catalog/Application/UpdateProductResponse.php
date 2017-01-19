<?php
namespace AMD\Catalog\Application;

class UpdateProductResponse
{
    /** @var string */
    private $id;

    /** @var string */
    private $description;

    /** @var string */
    private $familyId;

    public function __construct(string $id, string $description, string $familyId)
    {
        $this->id = $id;
        $this->description = $description;
        $this->familyId = $familyId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFamilyId(): string
    {
        return $this->familyId;
    }
}