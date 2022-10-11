<?php

namespace App\Security\Voter;

use App\Entity\Customers;
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
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Customers;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $customer = $subject;
        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::VIEW => $this->canView(),
            self::EDIT => $this->canEdit($customer, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canView(): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return false;
    }

    private function canEdit($customer, $user): bool
    {
        return $customer === $user->getCustomers();
    }
}
