<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionProperty;

interface Builder
{
    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAttribute $attribute): void;
}
