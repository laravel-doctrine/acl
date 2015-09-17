<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\ConfigAnnotation;
use ReflectionProperty;

class ManyToOneBuilder implements Builder
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
     * @param ConfigAnnotation   $annotation
     */
    public function build(ClassMetadata $metadata, ReflectionProperty $property, ConfigAnnotation $annotation)
    {
        $builder = new AssociationBuilder(
            new ClassMetadataBuilder($metadata),
            [
                'fieldName'    => $property->getName(),
                'targetEntity' => $annotation->getTargetEntity($this->config),
            ],
            ClassMetadata::MANY_TO_ONE
        );

        if (isset($annotation->inversedBy) && $annotation->inversedBy) {
            $builder->inversedBy($annotation->inversedBy);
        }

        $builder->build();
    }
}
