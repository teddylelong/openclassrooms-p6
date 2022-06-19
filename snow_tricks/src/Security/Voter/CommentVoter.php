<?php

namespace App\Security\Voter;

use App\Entity\Comment;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class CommentVoter extends Voter
{
    public const DELETE = 'COMMENT_DELETE';
    public const UPDATE = 'COMMENT_UPDATE';

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE, self::UPDATE])
            && $subject instanceof \App\Entity\Comment;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Comment $subject */
       // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::DELETE:
                return $this->canDelete();
                break;
            case self::UPDATE:
                return $this->canUpdate();
                break;
        }

        return false;
    }

    /**
     * @return bool
     */
    private function canDelete(): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if ($this->security->isGranted('ROLE_MODO')) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    private function canUpdate(): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        if ($this->security->isGranted('ROLE_MODO')) {
            return true;
        }
        return false;
    }
}
