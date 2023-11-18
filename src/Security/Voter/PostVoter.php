<?php declare(strict_types=1);

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\{Post, User};

class PostVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Post;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $post = $subject;
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::EDIT   => $this->canView($post, $user),
            self::VIEW   => $this->canEdit($post, $user),
            self::DELETE => $this->canDelete($post, $user),
            default      => false,
        };

    }

    private function canView(Post $post, UserInterface $user): bool
    {
        if ($this->canEdit($post, $user)) {
            return true;
        }
        return false;
    }

    private function canEdit(Post $post, UserInterface $user): bool
    {
        return $user === $post->getUser();
    }

    private function canDelete(Post $post, UserInterface $user): bool
    {
        if ($this->canEdit($post, $user)) {
            return true;
        }
        return false;
    }
}
