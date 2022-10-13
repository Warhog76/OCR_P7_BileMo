<?php

namespace App\Security\Voter;

use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['SHOW', 'DELETE'])
            && $subject instanceof Users;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $customer = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$customer instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            'SHOw', 'DELETE' => $subject->getCustomers()->getId() == $customer->getId(),
            default => false,
        };
    }
}
