<?php


namespace App\Serializer;


use ApiPlatform\Core\Exception\RuntimeException;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\Course;
use App\Entity\EducationEvent;
use App\Entity\JoinAction;
use App\Entity\Membership;
use App\Entity\NotifyWish;
use App\Entity\Person;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class PeopleContextBuilder implements SerializerContextBuilderInterface
{
    /** @var SerializerContextBuilderInterface */
    private $decorated;
    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /**
     * PeopleContextBuilder constructor.
     * @param SerializerContextBuilderInterface $decorated
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(SerializerContextBuilderInterface $decorated, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        $classes = [
            Person::class,
            Course::class,
            EducationEvent::class,
            NotifyWish::class,
            Membership::class
        ];

        if(in_array($resourceClass, $classes)) {
            try {
                if($this->authorizationChecker->isGranted('ROLE_ADMIN')){
                    if($normalization){
                        $context['groups'][] = 'admin:output';
                    }else{
                        $context['groups'][] = 'admin:input';
                    }
                }else if($this->authorizationChecker->isGranted('ROLE_USER')){
                    if($normalization){
                        $context['groups'][] = 'user:output';
                    }else{
                        $context['groups'][] = 'user:input';
                    }
                }else{
                    $context['groups'][] = 'anonymous:input';
                }
            } catch (AuthenticationCredentialsNotFoundException $exception) {
                if(!$normalization){
                    $context['groups'][] = 'anonymous:input';
                }
            }
        }
        return $context;
    }

}
