<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Permission as PermissionContract;

#[ORM\Entity]
class Permission implements PermissionContract
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    protected int|null $id = null;

    #[ORM\Column(type: 'string')]
    protected string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
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
}
