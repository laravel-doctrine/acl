<?php

declare(strict_types=1);

namespace Workbench\App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Mappings\HasPermissions as MappingsHasPermissions;
use LaravelDoctrine\ACL\Permissions\HasPermissions;

#[ORM\Entity]
#[ORM\Table(name: 'roles')]
class Role implements RoleContract, HasPermissionsContract
{
    use HasPermissions;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int|null $id = null;

    #[ORM\Column(name: 'name', type: 'string', unique: true)]
    protected string $name;

    #[MappingsHasPermissions()]
    public Collection $permissions;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->permissions = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function setPermissions($permissions): void
    {
        $this->permissions = is_array($permissions) ? new ArrayCollection($permissions) : $permissions;
    }
}
