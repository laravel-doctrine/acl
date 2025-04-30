<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\FieldBuilder;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository as Config;
use LaravelDoctrine\ACL\Attribute\MappingAttribute;
use ReflectionProperty;

class JsonArrayBuilder implements Builder
{
    public function __construct(protected Config $config)
    {
    }

    public function build(ClassMetadata $metadata, ReflectionProperty $property, MappingAttribute $attribute): void
    {
        $builder = new FieldBuilder(
            new ClassMetadataBuilder($metadata),
            [
                'fieldName' => $property->getName(),
                'type'      => Types::JSON,
            ],
        );

        $builder->build();
    }
}
