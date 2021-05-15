<?php

namespace App\Validator;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidProductValidator extends ConstraintValidator
{
    private $entityManager;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }
    public function validate($value, Constraint $constraint)
    {

        /* @var $constraint \App\Validator\IsValidProduct */

        if (!$value instanceof Order) {
            throw new \LogicException('Only CheeseListing is supported');
        }

        if($this->security->isGranted('ROLE_PROVIDER')) {
            $this->context->buildViolation('Cannot order product because you are provider')
                ->atPath('owner')
                ->addViolation();
            return;
        }

        $orderProducts = $value->getOrderProducts();

        foreach ($orderProducts as $orderProduct) {
            if($orderProduct->getQuantity() > $orderProduct->getProduct()->getQuantity()) {
                $this->context->buildViolation("Quantity not available for product Id {$orderProduct->getProduct()->getId()}")
                    ->addViolation();
                return;
            }
        }
    }
}
