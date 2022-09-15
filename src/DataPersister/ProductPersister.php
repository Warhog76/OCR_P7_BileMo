<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;

class ProductPersister implements DataPersisterInterface
{
    private EntityManagerInterface $_entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->_entityManager = $entityManager;
    }

    public function supports($data): bool
    {
        return $data instanceof Products;
    }

    public function persist($data)
    {
        $data->setCreatedAt(new \DateTimeImmutable());
        $data->setUpdatedAt(new \DateTimeImmutable());

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    public function remove($data)
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}
