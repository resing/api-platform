<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\OrderRepository;
use App\Validator\IsValidProduct;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Ulid;
use App\Doctrine\OrderSetOwnerListener;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"order:read"}},
 *     denormalizationContext={"groups"={"order:write"}},
 *      itemOperations={
 *          "get",
 *          "delete",
 *     },
 *     collectionOperations={
 *          "get",
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
     * @ORM\Column(type="integer")
     * @Groups({"order:read", "order:write"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="orders")
     * @Groups({"admin:read","order:read", "order:write"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @Groups({"admin:read"})
     */
    private $owner;

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

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
}
