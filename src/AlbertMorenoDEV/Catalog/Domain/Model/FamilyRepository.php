<?php
namespace AMD\Catalog\Domain\Model;

interface FamilyRepository
{
    public function add(Family $family);
    public function findAll();
}