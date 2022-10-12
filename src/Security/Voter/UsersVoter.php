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
        $currentUser = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            'MODIFY', 'DELETE' => $subject->getCustomers()->getId() == $currentUser->getUserIdentifier(),
            default => false,
        };
    }
}
