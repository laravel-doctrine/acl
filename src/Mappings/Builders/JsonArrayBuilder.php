<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionProperty;

class JsonArrayBuilder implements Builder
{
    /**
     * @param ClassMetadata $metadata
     * @param ReflectionProperty $property
     * @param ConfigAttribute $attribute
     */
    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAttribute $attribute)
    {
        $builder = new FieldBuilder(
            new ClassMetadataBuilder($metadata),
            [
                'fieldName' => $property->getName(),
                'type'      => Types::JSON,
            ]
        );

        $builder->build();
    }
}
