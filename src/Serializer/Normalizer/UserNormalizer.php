<?php

namespace App\Serializer\Normalizer;


use App\Entity\User;

use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;


class UserNormalizer implements ContextAwareNormalizerInterface, CacheableSupportsMethodInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;
    private const ALREADY_CALLED = 'USER_OWNER_CALLED';
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function supportsNormalization($data, string $format = null, array $context = [])
    {
        if (isset($context[self::ALREADY_CALLED])) {
            return false;
        }
        return $data instanceof User;
    }

    public function normalize($object, string $format = null, array $context = [])
    {
        $isOwner = $this->isOwner($object);
        if ($isOwner) {
            $context['groups'][] = 'owner:read';
        }
        $context[self::ALREADY_CALLED] = true;
        $data = $this->normalizer->normalize($object, $format, $context);

        // Here: add, edit, or delete some data

        return $data;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return false;
    }

    public function isOwner(User $user)
    {
        /** @var User|null $authenticatedUser */
        $authenticatedUser = $this->security->getUser();
        if (!$authenticatedUser) {
            return false;
        }
        return $authenticatedUser->getEmail() === $user->getEmail();
    }
}
