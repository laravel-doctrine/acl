<?php

declare(strict_types=1);

namespace Workbench\App\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Permissions\HasPermissions;

use function is_array;

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

    /** @var Collection<int, string> */
    #[ACL\HasPermissions()]
    public Collection $permissions;

    public function __construct(string $name)
    {
        $this->name        = $name;
        $this->permissions = new ArrayCollection();
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): int|null
    {
        return $this->id;
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
}
