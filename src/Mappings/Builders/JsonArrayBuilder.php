<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionProperty;

class JsonArrayBuilder implements Builder
{
    public function __construct(protected Repository $config)
    {
    }

    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAttribute $attribute): void
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
