<?php

namespace LaravelDoctrine\ACL\Permissions;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Contracts\Permission;

class DoctrinePermissionDriver implements PermissionDriver
{
    public function __construct(protected ManagerRegistry $registry, protected Repository $config)
    {
    }

    public function getAllPermissions(): Collection
    {
        if ($this->getRepository()) {
            try {
                $permissions = $this->getRepository()->findAll();
            } catch (TableNotFoundException $e) {
                $permissions = [];
            }

            return new Collection(
                $this->mapToArrayOfNames($permissions)
            );
        }

        return new Collection;
    }

    protected function mapToArrayOfNames(array $permissions): array
    {
        $permissions = array_map(function (Permission $permission) {
            return $permission->getName();
        }, $permissions);

        return $permissions;
    }

    protected function getEntityName(): ?string
    {
        return $this->config->get('acl.permissions.entity');
    }

    protected function getEntityManager(): ?EntityManagerInterface
    {
        return $this->registry->getManagerForClass(
            $this->getEntityName()
        );
    }

    protected function getRepository(): ?EntityRepository
    {
        if ($this->getEntityManager()) {
            $metadata = $this->getEntityManager()->getClassMetadata($this->getEntityName());

            return new EntityRepository(
                $this->getEntityManager(),
                $metadata
            );
        }

        return null;
    }
}
