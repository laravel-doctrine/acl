<?php

declare(strict_types=1);

namespace Workbench\App\Entities;

use Doctrine\ORM\Mapping as ORM;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use LaravelDoctrine\ACL\Attribute as ACL;
use LaravelDoctrine\ACL\Contracts\HasPermissions;
use LaravelDoctrine\ACL\Permissions\WithPermissions;
use LaravelDoctrine\ORM\Auth\Authenticatable;
use LaravelDoctrine\ORM\Notifications\Notifiable;

#[ORM\Entity]
#[ORM\Table()]
class UserJsonPermissions implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, HasPermissions
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use Notifiable;
    use WithPermissions;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int|null $id = null;

    #[ORM\Column(name: 'name')]
    public string $name;

    #[ORM\Column(name: 'email')]
    public string $email;

    /** @var array<string> */
    #[ACL\HasPermissions(inversedBy: 'users')]
    public array $permissions = [];

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
}
