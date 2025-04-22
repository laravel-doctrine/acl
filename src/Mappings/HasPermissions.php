<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings;

use Attribute;
use Illuminate\Contracts\Config\Repository;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class HasPermissions extends RelationAttribute
{
    public function __construct(
        string|null $targetEntity = null,
        string|null $mappedBy = null,
        public string|null $inversedBy = null,
        array|null $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        string|null $indexBy = null,
    ) {
        $this->targetEntity  = $targetEntity;
        $this->mappedBy      = $mappedBy;
        $this->cascade       = $cascade;
        $this->fetch         = $fetch;
        $this->orphanRemoval = $orphanRemoval;
        $this->indexBy       = $indexBy;
    }

    public function getTargetEntity(Repository $config): string|null
    {
        // Config driver has no target entity
        if ($config->get('acl.permissions.driver', 'config') === 'config') {
            return null;
        }

        return $this->targetEntity ?: $config->get('acl.permissions.entity', 'Permission');
    }
}
