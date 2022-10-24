<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Customers;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class UsersProcessor implements ProcessorInterface
{
    protected EntityManagerInterface $entityManager;
    protected UserPasswordHasherInterface $userPasswordHasher;
    protected TokenInterface $token;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return void
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $data->setRoles(['ROLE_USER']);

        if ($data->getPassword()) {
            $data->setPassword(
                $this->userPasswordHasher->hashPassword($data, $data->getPassword())
            );
            $data->eraseCredentials();
        }

        $customer = $this->token->getUser();
        if ($customer instanceof Customers) {
            $data->setRelation($customer);
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
}
