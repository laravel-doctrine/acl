<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionProperty;

class ManyToOneBuilder implements Builder
{
    public function __construct(protected Repository $config)
    {
    }

    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAttribute $attribute): void
    {
        $builder = new AssociationBuilder(
            new ClassMetadataBuilder($metadata),
            [
                'fieldName'    => $property->getName(),
                'targetEntity' => $attribute->getTargetEntity($this->config),
            ],
            ClassMetadata::MANY_TO_ONE,
        );

        if (isset($attribute->inversedBy) && $attribute->inversedBy) {
            $builder->inversedBy($attribute->inversedBy);
        }

        $builder->build();
    }
}
