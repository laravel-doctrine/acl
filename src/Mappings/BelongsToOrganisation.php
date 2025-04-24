<?php

namespace LaravelDoctrine\ACL\Mappings;

use Attribute;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\RelationAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class BelongsToOrganisation extends RelationAttribute
{
    /**
     * @var string
     */
    public $mappedBy = 'users';

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

    /**
     * @param Repository $config
     *
     * @return mixed
     */
    public function getTargetEntity(Repository $config)
    {
        return $this->targetEntity ?: $config->get('acl.organisations.entity', 'Organisation');
    }
}
