<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[ApiResource(
    paginationClientItemsPerPage: true,
    paginationItemsPerPage: 5,
    paginationMaximumItemsPerPage: 10,
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"
)]
#[GetCollection(
    normalizationContext: [
        'groups' => 'user:collection:read', ],
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"
)]
#[Get(
    normalizationContext: [
        'groups' => ['user:item:read', 'user:collection:read', 'customer:collection:read'], ],
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')"
)]
#[Post(
    security: "is_granted('ROLE_ADMIN')",
    securityPostDenormalize: "is_granted('ROLE_ADMIN') and (object.owner == user and previous_object.owner == user)",
    securityPostDenormalizeMessage: 'Sorry, but you are not the actual customer of this user.'
)]
#[Delete(
    security: "is_granted('ROLE_ADMIN')"
)]
#[ApiFilter(
    SearchFilter::class, properties: [
        'id' => 'exact',
        'customers.surname' => 'exact', ]
)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:collection:read', 'user:item:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(
        min: '5',
        max: '15',
        minMessage: 'Your username must contain at least 5 characters and maximum 15 characters'
    )]
    #[Groups(['user:collection:read', 'user:write'])]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: '10',
        minMessage: 'Your password must contain at least 10 characters'
    )]
    #[Assert\NotNull]
    #[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.'
    )]
    #[Groups(['user:item:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank]
    #[Groups(['user:item:read'])]
    private ?array $roles = [];

    #[ORM\ManyToMany(targetEntity: Customers::class, inversedBy: 'users')]
    #[Groups(['customer:collection:read'])]
    private Collection $customers;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection<int, Customers>
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customers $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers->add($customer);
        }

        return $this;
    }

    public function removeCustomer(Customers $customer): self
    {
        $this->customers->removeElement($customer);

        return $this;
    }

    public function eraseCredentials()
    {
    }
}
