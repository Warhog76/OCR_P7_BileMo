<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Customers;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UserPersister implements ContextAwareDataPersisterInterface
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasher;
    private TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, TokenInterface $token)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->token = $token;
    }

    public function supports($data, array $context = []): bool
    {
        return $data instanceof Users;
    }

    public function persist($data, array $context = [])
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
        $data->setRelation($customer->getId());

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
