<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use App\Validator\IsValidProduct;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use App\Doctrine\OrderSetOwnerListener;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"order:read"}},
 *     denormalizationContext={"groups"={"order:write"}},
 *      itemOperations={
 *          "get"= {
 *              "normalization_context"={"groups"={"order:read", "order:item:get"}},
 *           },
 *          "delete",
 *     },
 *     collectionOperations={
 *           "get" = {
 *                   "normalization_context"={"groups"={"order:read", "order:collection:get"}},
 *                  },
 *          "post"
 *     }
 * )
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\EntityListeners({OrderSetOwnerListener::class})
 * @ORM\Table(name="`order`")
 * @IsValidProduct()
 */
class Order
{
    public function __construct()
    {
        $this->reference = new Ulid();
        $this->orderProducts = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"order:read"})
     */
    private $reference;


    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @Groups({"admin:read","order:write"})
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity=OrderProduct::class, mappedBy="command", cascade={"persist"})
     * @Groups({"order:write"})
     */
    private $orderProducts;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|OrderProduct[]
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setCommand($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getCommand() === $this) {
                $orderProduct->setCommand(null);
            }
        }

        return $this;
    }
}
