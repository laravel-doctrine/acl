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
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations as BelongsToOrganisationsContract;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Organisations\BelongsToOrganisation;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ORM\Notifications\Notifiable;

use function is_array;

#[ORM\Entity]
#[ORM\Table()]
class User implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, HasRolesContract, HasPermissionsContract, BelongsToOrganisationsContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use Notifiable;
    use HasRoles;
    use HasPermissions;
    use BelongsToOrganisation;

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

    /** @var Collection<int, string> */
    #[ACL\HasPermissions()]
    public Collection $permissions;

    /** @var Collection<int, Organisation> */
    #[ACL\BelongsToOrganisations()]
    public Collection $organisations;

    public function __construct()
    {
        $this->roles         = new ArrayCollection();
        $this->permissions   = new ArrayCollection();
        $this->organisations = new ArrayCollection();
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

    /** @return Collection<int, string> */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    /** @param Collection<int, string>|string[] $permissions */
    public function setPermissions(Collection|array $permissions): self
    {
        $this->permissions = is_array($permissions) ? new ArrayCollection($permissions) : $permissions;

        return $this;
    }

    /** @return Collection<int, Organisation> */
    public function getOrganisations(): Collection
    {
        return $this->organisations;
    }

    /** @param Collection<int, Organisation>|Organisation[] $organisations */
    public function setOrganisations(Collection|array $organisations): self
    {
        $this->organisations = is_array($organisations) ? new ArrayCollection($organisations) : $organisations;

        return $this;
    }
}
