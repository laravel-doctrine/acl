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
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @param Repository $config
     */
    public function __construct(Repository $config)
    {
        $this->config = $config;
    }

    /**
     * @param ClassMetadata      $metadata
     * @param ReflectionProperty $property
     * @param ConfigAttribute   $attribute
     */
    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAttribute $attribute)
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
