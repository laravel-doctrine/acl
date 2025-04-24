<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionProperty;

interface Builder
{
    /**
     * @param ClassMetadata      $metadata
     * @param ReflectionProperty $property
     * @param ConfigAttribute   $attribute
     */
    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAttribute $attribute);
}
