<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Configurations;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Permissions\Driver\Doctrine;
use LaravelDoctrine\ACL\Permissions\Driver\PermissionDriver;
use RunTimeException;

class DoctrinePermissionsProvider implements PermissionsProvider
{
    public function __construct(protected ManagerRegistry $registry, protected Repository $config)
    {
    }

    /** @param mixed[] $settings */
    public function resolve(array $settings = []): PermissionDriver
    {
        $em       = $this->getEntityManager();
        $metadata = $em->getClassMetadata($this->getPermissionClass());

        return new Doctrine(new EntityRepository($em, $metadata));
    }

    protected function getPermissionClass(): string
    {
        $class = $this->config->get('acl.permissions.entity');

        if (! $class) {
            throw new RunTimeException(
                'Failed to configure doctrine permissions. No entity class provided.',
            );
        }

        return $class;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        $em = $this->registry->getManagerForClass($this->getPermissionClass());

        if (! $em) {
            throw new RunTimeException(
                'Failed to configure doctrine permissions.'
                . ' No entity manager found for entity: ' . $this->getPermissionClass() . '.',
            );
        }

        return $em;
    }
}
