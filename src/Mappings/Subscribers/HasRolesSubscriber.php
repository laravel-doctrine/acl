<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;
use LaravelDoctrine\ACL\Mappings\HasRoles;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;

class HasRolesSubscriber extends MappedEventSubscriber
{
    /**
     * @return string
     */
    public function getAttributeClass()
    {
        return HasRoles::class;
    }

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
     * @param ConfigAttribute $attribute
     *
     * @return string
     */
    protected function getBuilder(ConfigAttribute $attribute)
    {
        return ManyToManyBuilder::class;
    }
}
