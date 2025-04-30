<?php

declare(strict_types=1);

namespace App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Attribute as ACL;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Permissions\Permission;
use LaravelDoctrine\ACL\Permissions\WithPermissions;

use function is_array;

#[ORM\Entity]
class Role implements RoleContract
{
    use WithPermissions;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected int|null $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    protected string $name;

    /** @var Collection<int, string> */
    #[ACL\HasPermissions]
    public Collection $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
    }

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /** @return Collection<int, Permission> */
    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    /** @param Collection<int, Permission>|Permission[] $permissions */
    public function setPermissions(Collection|array $permissions): self
    {
        $this->permissions = is_array($permissions) ? new ArrayCollection($permissions) : $permissions;

        return $this;
    }
}


