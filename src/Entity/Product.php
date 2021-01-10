<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
 * @ORM\Id
 * @ORM\GeneratedValue
 * @ORM\Column(type="integer")
 */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\NotNull()
     * @Assert\Length(min = 2, max = 50, minMessage = "The product name must be at least {{ limit }} characters long", maxMessage = "The product name cannot be longer than {{ limit }} characters")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull()
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\NotNull())
     */
    private $brand;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
