<?php

namespace LaravelDoctrine\ACL\Mappings;

use Attribute;
use Doctrine\Common\Annotations\Annotation;
use Illuminate\Contracts\Config\Repository;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class HasRoles extends RelationAnnotation
{
    /**
     * @var string
     */
    public $inversedBy;

    /**
     * @param string $inversedBy
     */
    public function __construct(
        ?string $targetEntity = null,
        ?array $cascade = null,
        string $fetch = 'LAZY',
        bool $orphanRemoval = false,
        ?string $indexBy = null,
        ?string $mappedBy = null,
        string $inversedBy = 'users'
    ) {
        $this->targetEntity = $targetEntity;
        $this->cascade = $cascade;
        $this->fetch = $fetch;
        $this->orphanRemoval = $orphanRemoval;
        $this->indexBy = $indexBy;
        $this->mappedBy = $mappedBy;
        $this->inversedBy = $inversedBy;
    }


    /**
     * @param Repository $config
     *
     * @return mixed
     */
    public function getTargetEntity(Repository $config)
    {
        return $this->targetEntity ?: $config->get('acl.roles.entity', 'Role');
    }
}
