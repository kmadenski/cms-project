<?php


namespace App\Serializer;


use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\Course;
use App\Entity\EducationEvent;
use App\Entity\JoinAction;
use App\Entity\NotifyWish;
use App\Entity\Person;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

class JoinActionContextBuilder implements SerializerContextBuilderInterface
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

        if(JoinAction::class === $resourceClass) {
            if($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                if($normalization){
                    $context['groups'][] = 'admin:output';
                }else{
                    $context['groups'][] = 'admin:input';
                }
            }else if($this->authorizationChecker->isGranted('ROLE_USER')) {
                if(isset($context['item_operation_name'])){
                    if($context['item_operation_name'] === 'accept'){
                        $context['groups'][] = 'user:action.accept';
                    }else if($context['item_operation_name'] === 'reject'){
                        $context['groups'][] = 'user:action.reject';
                    }
                }
                else{
                    if($normalization){
                        $context['groups'][] = 'user:output';
                    }else{
                        $context['groups'][] = 'user:input';
                    }
                }
            }
        }
        return $context;
    }
}
