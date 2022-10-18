<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\DataPersister\UserPersister;
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
    paginationMaximumItemsPerPage: 10
)]
#[GetCollection(
    normalizationContext: [
        'groups' => 'user:collection:read', ],
    security: "is_granted('ROLE_ADMIN')"
)]
#[Get(
    normalizationContext: [
        'groups' => ['user:item:read', 'user:collection:read', 'customer:collection:read'], ],
    security: "is_granted('SHOW', object)",
    securityMessage: 'Sorry, but you are not the actual customer of this user.'
)]
#[Post(
    denormalizationContext: [
        'groups' => 'user:write', ],
    securityMessage: 'Only admins can add users.',
    securityPostDenormalize: "is_granted('ROLE_ADMIN')"
)]
#[Delete(
    securityPostDenormalize: "is_granted('DELETE', object)",
    securityPostDenormalizeMessage: 'Sorry, but you are not the actual customer of this user.'
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
        min: '8',
        minMessage: 'Your password must contain at least 8 characters'
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
    #[Groups(['user:item:read', 'user:write'])]
    private ?array $roles = [];

    #[ORM\ManyToOne(inversedBy: 'relation')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['customer:collection:read'])]
    private ?Customers $relation = null;

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
        return (string) $this->email;
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

    public function eraseCredentials()
    {
    }

    public function getRelation(): ?Customers
    {
        return $this->relation;
    }

    public function setRelation(?Customers $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
