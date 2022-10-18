<?php

namespace App\Security\Voter;

use App\Entity\Customers;
use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UsersVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['SHOW', 'DELETE'])
            && $subject instanceof Users;
    }

    /**
     * @param string $attribute
     * @param Users $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $customer = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$customer instanceof Customers) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            'SHOW', 'DELETE' => $subject->getRelation()->getId() == $customer->getId(),
            default => false,
        };
    }
}
