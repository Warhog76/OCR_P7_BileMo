<?php

namespace App\Security\Voter;

use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UsersVoter extends Voter
{
    public const EDIT = 'USER_EDIT';
    public const VIEW = 'USER_VIEW';

    private ?Security $security = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        return self::EDIT == $attribute
            && $subject instanceof Users;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$currentUser instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        $user = $subject;
        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::EDIT => $this->canEdit($currentUser, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canEdit($currentUser, $user): bool
    {
        return $currentUser === $user->getCustomers();
    }
}
