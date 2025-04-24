<?php

namespace LaravelDoctrine\ACL\Mappings;

use Attribute;
use Illuminate\Contracts\Config\Repository;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class HasPermissions extends RelationAttribute
{
    /**
     * @param string|null $targetEntity
     * @param string|null $mappedBy
     * @param array|null $cascade
     * @param string $fetch
     * @param bool $orphanRemoval
     * @param string|null $indexBy
     */
    public function __construct(
        ?string $targetEntity = null,
        ?string $mappedBy = null,
        ?array $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        ?string $indexBy = null
    ) {
        $this->targetEntity = $targetEntity;
        $this->mappedBy = $mappedBy;
        $this->cascade = $cascade;
        $this->fetch = $fetch;
        $this->orphanRemoval = $orphanRemoval;
        $this->indexBy = $indexBy;
    }

    /**
     * @param Repository $config
     *
     * @return mixed
     */
    public function getTargetEntity(Repository $config)
    {
        // Config driver has no target entity
        if ($config->get('acl.permissions.driver', 'config') === 'config') {
            return false;
        }

        return $this->targetEntity ?: $config->get('acl.permissions.entity', 'Permission');
    }
}