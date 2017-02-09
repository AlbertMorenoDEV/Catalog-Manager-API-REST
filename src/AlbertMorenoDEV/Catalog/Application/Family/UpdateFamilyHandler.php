<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyNotFoundException;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Common\Application\Command;
use AMD\Common\Application\CommandHandler;

class UpdateFamilyHandler implements CommandHandler
{
    /** @var FamilyRepository */
    private $repository;

    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Command|UpdateFamilyCommand $command
     * @throws \AMD\Catalog\Domain\Model\Family\FamilyNotFoundException
     */
    public function handle(Command $command)
    {
        /** @var Family $family */
        $family = $this->repository->findByFamilyId($command->getFamilyId());

        if (!$family) {
            throw new FamilyNotFoundException('No family found for id '.$command->getFamilyId());
        }

        $family->setName($command->getName());
        $this->repository->update($family);
    }
}