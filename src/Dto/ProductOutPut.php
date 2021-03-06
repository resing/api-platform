<?php


namespace App\Dto;


use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

class ProductOutPut
{
    /**
     * @var string
     * @Groups({"product:read", "order:item:get", "order:collection:get"})
     */
    public $name;

    /**
     * @var string
     * @Groups({"product:read", "order:item:get"})
     */
    public $description;

    /**
     * @var int
     * @Groups({"product:read", "admin:read", "order:item:get"})
     */
    public $price;

    /**
     * @var \DateTime
     * @Groups({"product:read", "order:item:ge"})
     */
    public $createdAt;

    /**
     * @var Category
     * @Groups({"product:read", "order:item:get"})
     */
    public $category;

    /**
     * @var User
     * @Groups({"admin:read"})
     */
    public $owner;

    /**
     * @var bool
     * @Groups("admin:read")
     */

    public $isPublished;

    /**
     * @var bool
     * @Groups({"product:read"})
     */
    public $unlimited;


    /**
     * @var int
     * @Groups({"product:read"})
     */
    public $quantity;


    public static function createFromEntity(Product $product)
    {
        $output = new ProductOutPut();
        $output->name = $product->getName();
        $output->owner = $product->getOwner();
        $output->category = $product->getCategory();
        $output->price = $product->getPrice();
        $output->createdAt = $product->getCreatedAt();
        $output->description = $product->getDescription();
        $output->isPublished = $product->getIsPublished();
        $output->unlimited = $product->getUnlimited();
        $output->quantity = $product->getQuantity();

        return $output;
    }
}
