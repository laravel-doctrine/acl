<?php

declare(strict_types=1);

namespace Workbench\App\Entities;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisation;
use LaravelDoctrine\ACL\Mappings\HasRoles as MappingsHasRoles;
use LaravelDoctrine\ACL\Organisations\BelongsToOrganisation as TraitBelongsToOrganisation;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ORM\Notifications\Notifiable;

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

    #[MappingsHasRoles()]
    public Collection $roles;

    #[ORM\Column(type: 'json')]
    public array $permissions = [];

    #[BelongsToOrganisation()]
    public ?Organisation $organisation = null;


    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function setRoles($roles): void
    {
        $this->roles = is_array($roles) ? new ArrayCollection($roles) : $roles;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

    public function getOrganisation(): ?Organisation
    {
        return $this->organisation;
    }

    public function setOrganisation($organisation): void
    {
        $this->organisation = $organisation;
    }
}
