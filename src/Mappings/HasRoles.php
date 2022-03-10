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
    public $inversedBy = 'users';

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
