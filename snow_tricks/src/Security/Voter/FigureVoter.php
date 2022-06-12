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
    public const VIEW = 'FIGURE_VIEW';
    public const EDIT = 'FIGURE_EDIT';
    public const DELETE = 'FIGURE_DELETE';
    public const UPDATE_STATUS = 'FIGURE_UPDATE';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::EDIT, self::DELETE, self::UPDATE_STATUS])
            && $subject instanceof \App\Entity\Figure;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            $user = new User();
        }

        /** @var Figure $figure */
        $figure = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                return $this->canView($figure, $user);
            case self::EDIT:
                return $this->canEdit($figure, $user);
            case self::DELETE:
                return $this->canDelete($figure, $user);
            case self::UPDATE_STATUS:
                return $this->canUpdate();
        }

        return false;
    }

    private function canView(Figure $figure, User $user): bool
    {
        if ($figure->getStatus() == Figure::STATUS_ACCEPTED) {
            return true;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if ($this->security->isGranted('ROLE_MODO')) {
            return true;
        }
        if ($user === $figure->getUser()) {
            return true;
        }
        return false;
    }

    private function canEdit(Figure $figure, User $user): bool
    {
        if ($user === $figure->getUser()) {
            return true;
        }
        if ($figure->getStatus() == Figure::STATUS_ACCEPTED) {
            return true;
        }
        if ($this->security->isGranted('ROLE_USER')) {
            return true;
        }
        return false;
    }

    private function canDelete(): bool
    {
        if ($this->security->isGranted('ROLE_USER')) {
            return true;
        }
        return false;
    }

    private function canUpdate(): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if ($this->security->isGranted('ROLE_MODO')) {
            return true;
        }

    }
}
