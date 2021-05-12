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
            $quantityProduct = $order->getProduct()->getQuantity();
            $order->getProduct()->setQuantity($quantityProduct - $order->getQuantity());
        }
    }
}
