<?php

namespace LaravelDoctrine\ACL\Mappings;

use Doctrine\Common\Annotations\Annotation;
use Illuminate\Contracts\Config\Repository;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class HasPermissions extends Annotation implements ConfigAnnotation
{
    /**
     * @var string
     */
    public $targetEntity;

    /**
     * @var string
     */
    public $inversedBy;

    /**
     * @var array<string>
     */
    public $cascade;

    /**
     * The fetching strategy to use for the association.
     * @var string
     * @Enum({"LAZY", "EAGER", "EXTRA_LAZY"})
     */
    public $fetch = 'LAZY';

    /**
     * @var bool
     */
    public $orphanRemoval = false;

    /**
     * @var string
     */
    public $indexBy;

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
