<?php
namespace AMD\Catalog\Application\Family;

use AMD\Catalog\Domain\Model\Family\Family;
use AMD\Catalog\Domain\Model\Family\FamilyRepository;
use AMD\Common\Application\Command;
use AMD\Common\Application\CommandHandler;

class AddFamilyHandler implements CommandHandler
{
    /** @var \AMD\Catalog\Domain\Model\Family\FamilyRepository */
    private $repository;

    /**
     * AddFamilyHandler constructor.
     * @param FamilyRepository $repository
     */
    public function __construct(FamilyRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Command|AddFamilyCommand $command
     */
    public function handle(Command $command)
    {
        $family = new Family($command->getFamilyId(), $command->getName());

        $this->repository->add($family);
    }
}