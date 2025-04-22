<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings;

use Attribute;
use Illuminate\Contracts\Config\Repository;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class BelongsToOrganisation extends RelationAttribute
{
    public function __construct(
        string|null $targetEntity = null,
        public string|null $mappedBy = 'users',
        array|null $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        string|null $indexBy = null,
    ) {
        $this->targetEntity  = $targetEntity;
        $this->cascade       = $cascade;
        $this->fetch         = $fetch;
        $this->orphanRemoval = $orphanRemoval;
        $this->indexBy       = $indexBy;
    }

    public function getTargetEntity(Repository $config): string|null
    {
        return $this->targetEntity ?: $config->get('acl.organisations.entity', 'Organisation');
    }
}
