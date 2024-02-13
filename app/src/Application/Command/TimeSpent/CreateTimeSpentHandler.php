<?php

namespace App\Application\Command\TimeSpent;

use App\Domain\Entity\Contractor;
use App\Domain\Entity\TimeSpent;
use App\Domain\Repository\ContractorRepositoryInterface;
use App\Domain\Repository\TimeSpentRepositoryInterface;
use App\Infrastructure\Repository\Contractor\ContractorRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CreateTimeSpentHandler
{

    public function __construct(private TimeSpentRepositoryInterface $repository, private ContractorRepositoryInterface $contractorRepository) {
    }

    public function __invoke(CreateTimeSpentCommand $command) {
        $timeSpent = new TimeSpent();
        $timeSpent->setDescription($command->getDescription());
        $timeSpent->setTime($command->getTime());
        $contractor = $this->contractorRepository->findOne($command->getContractorId());
        if (null === $contractor) {
            return;
        }
        $timeSpent->setContractor($contractor);
        $timeSpent->setCreatedAt(new \DateTime());
        $this->repository->save($timeSpent);
        return $timeSpent;
    }

}
