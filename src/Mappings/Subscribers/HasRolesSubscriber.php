<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;
use LaravelDoctrine\ACL\Mappings\ConfigAnnotation;
use LaravelDoctrine\ACL\Mappings\HasRoles;

class HasRolesSubscriber extends MappedEventSubscriber
{
    /**
     * @param $metadata
     *
     * @return bool
     */
    protected function shouldBeMapped(ClassMetadata $metadata)
    {
        return $this->getInstance($metadata) instanceof HasRolesContract;
    }

    /**
     * @return string
     */
    public function getAnnotationClass()
    {
        return HasRoles::class;
    }

    /**
     * @param ConfigAnnotation $annotation
     *
     * @return string
     */
    protected function getBuilder(ConfigAnnotation $annotation)
    {
        return ManyToManyBuilder::class;
    }
}
