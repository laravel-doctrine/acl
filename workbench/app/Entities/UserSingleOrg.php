<?php

declare(strict_types=1);

namespace Workbench\App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Organisations\BelongsToOrganisation as TraitBelongsToOrganisation;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ORM\Notifications\Notifiable;

use function is_array;

#[ORM\Entity]
#[ORM\Table()]
class UserSingleOrg implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, HasRolesContract, HasPermissionsContract, BelongsToOrganisationContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use Notifiable;
    use HasRoles;
    use HasPermissions;
    use TraitBelongsToOrganisation;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int|null $id = null;

    #[ORM\Column(name: 'name')]
    public string $name;

    #[ORM\Column(name: 'email')]
    public string $email;

    /** @var Collection<int, Role> */
    #[ACL\HasRoles()]
    public Collection $roles;

    /** @var array<string> */
    #[ORM\Column(type: 'json')]
    public array $permissions = [];

    #[ACL\BelongsToOrganisation()]
    public Organisation|null $organisation = null;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    /** @return Collection<int, Role> */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /** @param Collection<int, Role>|Role[] $roles */
    public function setRoles(Collection|array $roles): self
    {
        $this->roles = is_array($roles) ? new ArrayCollection($roles) : $roles;

        return $this;
    }

    /** @return array<string> */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /** @param array<string> $permissions */
    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function getOrganisation(): Organisation|null
    {
        return $this->organisation;
    }

    public function setOrganisation(Organisation|null $organisation): self
    {
        $this->organisation = $organisation;

        return $this;
    }
}
