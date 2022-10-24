<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Customers;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UsersPersister implements DataPersisterInterface
{
    protected EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $userPasswordHasher;
    protected TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, TokenInterface $token)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->token = $token;
    }

    public function supports($data): bool
    {
        return $data instanceof Users;
    }

    public function persist($data)
    {
        if ($data->getPassword()) {
            $data->setPassword(
                $this->userPasswordHasher->hashPassword($data, $data->getPassword())
            );
            $data->eraseCredentials();
        }

        $data->setRoles(['ROLE_USER']);

        $customer = $this->token->getUser();
        if (!$customer instanceof Customers) {
            return false;
        }
        $data->setRelation($customer);

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
