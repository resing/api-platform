<?php


namespace App\Serializer;


use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

final class AdminGroupContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;

    private $authorizationChecker;

    public function __construct(
        SerializerContextBuilderInterface $decorated,
        AuthorizationCheckerInterface $authorizationChecker
    ) {

        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $context['groups'] = $context['groups'] ?? [];
        $isAdmin = $this->authorizationChecker->isGranted('ROLE_ADMIN');

        if($isAdmin) {
            $context['groups'][] =  'admin:read';
        }

        $context['groups'] = array_unique($context['groups']);

        return $context;
    }
}
