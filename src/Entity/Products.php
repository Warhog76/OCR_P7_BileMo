<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\ProductsRepository;
use App\Services\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductsRepository::class)]
#[ApiResource(
    paginationClientItemsPerPage: true,
    paginationItemsPerPage: 5,
    paginationMaximumItemsPerPage: 10,
)]
#[GetCollection]
#[Get]
class Products
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['products:collection:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['products:collection:read', 'products:item:write'])]
    private ?string $manufacturer = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['products:collection:read', 'products:item:write'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Length(
        min: '10',
        minMessage: 'Your description must contain at least 10 characters'
    )]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Regex(
        pattern: '/[a-zA-Z0-9._\p{L}-]{1,20}/'
    )]
    #[Groups(['products:item:read', 'products:item:write'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['products:item:read', 'products:item:write'])]
    private ?string $screen = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'integer',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    #[Groups(['products:item:read', 'products:item:write'])]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $slug = null;

    #[ORM\ManyToMany(targetEntity: customers::class, inversedBy: 'products')]
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getManufacturer(): ?string
    {
        return $this->manufacturer;
    }

    public function setManufacturer(string $manufacturer): self
    {
        $this->manufacturer = $manufacturer;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getScreen(): ?string
    {
        return $this->screen;
    }

    public function setScreen(string $screen): self
    {
        $this->screen = $screen;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Initialize slug before persist or update.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function initializeSlug(): void
    {
        if (empty($this->slug)) {
            $slug = slugify::slug($this->name);
            $this->setSlug($slug);
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, customers>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(customers $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
        }

        return $this;
    }

    public function removeCustomer(customers $customer): self
    {
        $this->customers->removeElement($customer);

        return $this;
    }
}
