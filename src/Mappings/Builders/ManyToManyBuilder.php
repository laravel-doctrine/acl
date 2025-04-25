<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\ManyToManyAssociationBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionProperty;

class ManyToManyBuilder implements Builder
{
    public function __construct(protected Repository $config)
    {
    }

    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAttribute $attribute): void
    {
        $builder = new ManyToManyAssociationBuilder(
            new ClassMetadataBuilder($metadata),
            [
            'fieldName'    => $property->getName(),
                'targetEntity' => $attribute->getTargetEntity($this->config),
            ],
            ClassMetadata::MANY_TO_MANY
        );

        if (isset($attribute->inversedBy) && $attribute->inversedBy) {
            $builder->inversedBy($attribute->inversedBy);
        }

        if (isset($attribute->mappedBy) && $attribute->mappedBy) {
            $builder->mappedBy($attribute->mappedBy);
        }

        $builder->build();
    }
}
