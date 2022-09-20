<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;

class ProductPersister implements DataPersisterInterface
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function supports($data): bool
    {
        return $data instanceof Products;
    }

    public function persist($data)
    {
        $data->setCreatedAt(new \DateTimeImmutable());
        $data->setUpdatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
