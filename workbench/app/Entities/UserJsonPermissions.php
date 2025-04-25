<?php

declare(strict_types=1);

namespace Workbench\App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Auth\Passwords\CanResetPassword;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Mappings\HasPermissions as MappingsHasPermissions;
use LaravelDoctrine\ACL\Permissions\HasPermissions;
use LaravelDoctrine\ORM\Notifications\Notifiable;

#[ORM\Entity]
#[ORM\Table()]
class UserJsonPermissions implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, HasPermissionsContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use Notifiable;
    use HasPermissions;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int|null $id = null;

    #[ORM\Column(name: 'name')]
    public string $name;

    #[ORM\Column(name: 'email')]
    public string $email;

    #[MappingsHasPermissions(inversedBy: 'users')]
    public array $permissions = [];

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;
        return $this;
    }
}
