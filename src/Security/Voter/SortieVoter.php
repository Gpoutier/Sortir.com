<?php

namespace App\Security\Voter;

use App\Entity\Sortie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, $sortie): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $sortie instanceof \App\Entity\Sortie;
    }


    protected function voteOnAttribute(string $attribute, $sortie, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $this->canEdit($sortie,$user);
                break;
            case self::DELETE:
                return $this->canDelete($sortie,$user);
                break;
        }

        return false;
    }

    private function canDelete(Sortie $sortie,UserInterface $user):bool
    {
        if(($sortie->getOrganisateur() == $user)&& ($sortie->getEtat()->getIdEtat()==1))
            return true;
        else
            return false;
    }

    private function canEdit(Sortie $sortie,UserInterface $user):bool
    {
        if($sortie->getOrganisateur() == $user)
            return true;
        else
            return false;
    }
}
