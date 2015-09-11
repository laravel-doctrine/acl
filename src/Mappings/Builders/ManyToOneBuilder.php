<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\ORM\Mapping\Builder\AssociationBuilder;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
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
     * @param Annotation         $annotation
     */
    public function build(ClassMetadata $metadata, ReflectionProperty $property, Annotation $annotation)
    {
        $builder =  new AssociationBuilder(
            new ClassMetadataBuilder($metadata),
            [
                'fieldName'    => $property->getName(),
                'targetEntity' => $annotation->targetEntity
            ],
            ClassMetadata::MANY_TO_ONE
        );

        if (isset($annotation->inversedBy) && $annotation->inversedBy) {
            $builder->inversedBy($annotation->inversedBy);
        }

        $builder->build();
    }
}
