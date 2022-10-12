<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class UserPersister implements DataPersisterInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function supports($data): bool
    {
        return $data instanceof Users;
    }

    public function persist($data)
    {
        $data->addCustomer();

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
