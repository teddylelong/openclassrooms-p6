<?php

namespace App\Security\Voter;

use App\Entity\Figure;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class FigureVoter extends Voter
{
    public const EDIT = 'FIGURE_EDIT';
    public const CREATE = 'FIGURE_CREATE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::CREATE])
            && $subject instanceof \App\Entity\Figure;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Figure $figure */
        $figure = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($figure, $user);
            case self::CREATE:
                return $this->canCreate();
        }

        return false;
    }

    private function canEdit(Figure $figure, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        return $user === $figure->getUser();
    }

    private function canCreate(): bool
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            return true;
        }
    }
}
