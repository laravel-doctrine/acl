<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\Persistence\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Mappings\MappingAttribute;
use ReflectionProperty;

interface Builder
{
    public function build(ClassMetadata $metadata, ReflectionProperty $property, MappingAttribute $attribute): void;
}
