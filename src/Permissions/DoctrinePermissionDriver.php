<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Permissions;

use Doctrine\DBAL\Exception\TableNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Contracts\Permission;

use function array_map;

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
            } catch (TableNotFoundException) {
                $permissions = [];
            }

            return new Collection(
                $this->mapToArrayOfNames($permissions),
            );
        }

        return new Collection();
    }

    /**
     * @param array<int,Permission> $permissions
     *
     * @return array<int, string>
     */
    protected function mapToArrayOfNames(array $permissions): array
    {
        $permissions = array_map(static function (Permission $permission) {
            return $permission->getName();
        }, $permissions);

        return $permissions;
    }

    protected function getEntityName(): string|null
    {
        return $this->config->get('acl.permissions.entity');
    }

    protected function getEntityManager(): EntityManagerInterface|null
    {
        return $this->registry->getManagerForClass(
            $this->getEntityName(),
        );
    }

    protected function getRepository(): EntityRepository|null
    {
        if ($this->getEntityManager()) {
            $metadata = $this->getEntityManager()->getClassMetadata($this->getEntityName());

            return new EntityRepository(
                $this->getEntityManager(),
                $metadata,
            );
        }

        return null;
    }
}
