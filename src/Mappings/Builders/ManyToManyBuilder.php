<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\ManyToManyAssociationBuilder;
use Doctrine\ORM\Mapping\ClassMetadata as OrmClassMetadata;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository as Config;
use LaravelDoctrine\ACL\Attribute\MappingAttribute;
use ReflectionProperty;

class ManyToManyBuilder implements Builder
{
    public function __construct(protected Config $config)
    {
    }

    public function build(ClassMetadata $metadata, ReflectionProperty $property, MappingAttribute $attribute): void
    {
        $builder = new ManyToManyAssociationBuilder(
            new ClassMetadataBuilder($metadata),
            [
                'fieldName'    => $property->getName(),
                'targetEntity' => $attribute->getTargetEntity($this->config),
            ],
            OrmClassMetadata::MANY_TO_MANY,
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
