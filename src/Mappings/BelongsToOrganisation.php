<?php

namespace LaravelDoctrine\ACL\Mappings;

use Attribute;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\RelationAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class BelongsToOrganisation extends RelationAttribute
{
    public ?string $mappedBy = 'users';

    public function __construct(
        ?string $targetEntity = null,
        ?string $mappedBy = 'users',
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

    public function getTargetEntity(Repository $config): ?string
    {
        return $this->targetEntity ?: $config->get('acl.organisations.entity', 'Organisation');
    }
}
