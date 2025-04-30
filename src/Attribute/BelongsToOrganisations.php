<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Attribute;

use Attribute;
use Illuminate\Contracts\Config\Repository as Config;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class BelongsToOrganisations extends RelationAttribute
{
    public function __construct(
        string|null $targetEntity = null,
        string|null $mappedBy = null,
        array|null $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        string|null $indexBy = null,
        public string $inversedBy = 'users',
    ) {
        $this->targetEntity  = $targetEntity;
        $this->mappedBy      = $mappedBy;
        $this->cascade       = $cascade;
        $this->fetch         = $fetch;
        $this->orphanRemoval = $orphanRemoval;
        $this->indexBy       = $indexBy;
    }

    public function getTargetEntity(Config $config): string|null
    {
        return $this->targetEntity ?: $config->get('acl.organisations.entity', 'Organisation');
    }
}
