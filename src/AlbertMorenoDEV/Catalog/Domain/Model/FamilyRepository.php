<?php
namespace AMD\Catalog\Domain\Model;

use AMD\Catalog\Domain\Model\Family\FamilyId;

interface FamilyRepository
{
    public function nextIdentity(): FamilyId;
    public function add(Family $family);
    public function update(Family $family);
    public function remove(Family $family);
    public function findAll();
    public function findById(FamilyId $familyId);
}