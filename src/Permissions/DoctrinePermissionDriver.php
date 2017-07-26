<?php

namespace LaravelDoctrine\ACL\Permissions;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\Collection;
use LaravelDoctrine\ACL\Contracts\Permission;
use Exception;
use Illuminate\Contracts\Logging\Log;

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
     * @var Log
     */
    protected $log;

    /**
     * @param ManagerRegistry $registry
     * @param Repository      $config
     * @param Log             $log
     */
    public function __construct(ManagerRegistry $registry, Repository $config, Log $log)
    {
        $this->registry = $registry;
        $this->config   = $config;
        $this->log      = $log;
    }

    /**
     * @return Collection
     */
    public function getAllPermissions()
    {
        if ($this->getRepository()) {
            try {
                $permissions = $this->getRepository()->findAll();

                return new Collection(
                    $this->mapToArrayOfNames($permissions)
                );
            } catch (Exception $e) {
                // Catch any exception as this method is called very early in the
                // booting process of laravel, making commands unusable if
                // its not caught.
                $this->log->error($e);
            }
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
