<?php

namespace App\Security\Authorization;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class WithLogVoter extends Voter
{
    private const WITH_LOG = 'with_log';

    protected function supports(string $attribute, $subject)
    {
        if ($attribute !== self::WITH_LOG) {
            return false;
        }

        if (!$subject instanceof Request) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        if ($subject->query->has('with_log')) {
            return true;
        }

        return false;
    }
}