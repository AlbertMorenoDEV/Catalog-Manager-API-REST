<?php
namespace AMD\Catalog\Domain\Model;

interface FamilyRepository
{
    public function add(Family $family);
    public function update(Family $family);
    public function remove(Family $family);
    public function findAll();
    public function findById($familyId);
}