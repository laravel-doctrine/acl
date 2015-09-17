<?php

namespace LaravelDoctrine\ACL\Permissions;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;

class DoctrinePermissionDriver implements PermissionDriver
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @param ManagerRegistry $registry
     * @param Repository      $config
     */
    public function __construct(ManagerRegistry $registry, Repository $config)
    {
        $this->registry = $registry;
        $this->config = $config;
    }

    /**
     * @return Collection
     */
    public function getAllPermissions()
    {
        if ($this->getRepository()) {
            $permissions = $this->getRepository()->findAll();

            return new Collection(
                $this->mapToArrayOfNames($permissions)
            );
        }

        return new Collection;
    }

    /**
     * @param $permissions
     *
     * @return array
     */
    protected function mapToArrayOfNames($permissions)
    {
        $permissions = array_map(function (Permission $permission) {
            return $permission->getName();
        }, $permissions);

        return $permissions;
    }

    /**
     * @return string
     */
    protected function getEntityName()
    {
        return $this->config->get('acl.permissions.entity');
    }

    /**
     * @return EntityManagerInterface|null
     */
    protected function getEntityManager()
    {
        return $this->registry->getManagerForClass(
            $this->getEntityName()
        );
    }

    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        if ($this->getEntityManager()) {
            $metadata = $this->getEntityManager()->getClassMetadata($this->getEntityName());

            $schemaManager = $this->getEntityManager()->getConnection()->getSchemaManager();
            if ($schemaManager->tablesExist([$metadata->getTableName()]) == true) {
                return new EntityRepository(
                    $this->getEntityManager(),
                    $metadata
                );
            }
        }
    }
}
