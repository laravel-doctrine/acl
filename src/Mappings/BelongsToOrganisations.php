<?php

namespace LaravelDoctrine\ACL\Mappings;

use Attribute;
use Illuminate\Contracts\Config\Repository;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class BelongsToOrganisations extends RelationAttribute
{
    /**
     * @var string
     */
    public $inversedBy = 'users';

    /**
     * @param string|null $targetEntity
     * @param string|null $mappedBy
     * @param array|null $cascade
     * @param string $fetch
     * @param bool $orphanRemoval
     * @param string|null $indexBy
     * @param string $inversedBy
     */
    public function __construct(
        ?string $targetEntity = null,
        ?string $mappedBy = null,
        ?array $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        ?string $indexBy = null,
        string $inversedBy = 'users'
    ) {
        $this->targetEntity = $targetEntity;
        $this->mappedBy = $mappedBy;
        $this->cascade = $cascade;
        $this->fetch = $fetch;
        $this->orphanRemoval = $orphanRemoval;
        $this->indexBy = $indexBy;
        $this->inversedBy = $inversedBy;
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
