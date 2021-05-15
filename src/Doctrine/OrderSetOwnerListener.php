<?php


namespace App\Doctrine;


use App\Entity\Order;
use Symfony\Component\Security\Core\Security;

class OrderSetOwnerListener
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(Order $order)
    {
        if ($order->getOwner()) {
            return;
        }

        if ($this->security->getUser()) {
            $order->setOwner($this->security->getUser());

            foreach ($order->getOrderProducts() as $orderProduct) {
                $quantityProduct = $orderProduct->getProduct()->getQuantity();
                $orderProduct->getProduct()->setQuantity($quantityProduct - $orderProduct->getQuantity());
            }
        }
    }
}
