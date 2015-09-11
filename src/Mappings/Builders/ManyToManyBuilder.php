<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\ManyToManyAssociationBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionProperty;

class ManyToManyBuilder implements Builder
{
    /**
     * @param ClassMetadata      $metadata
     * @param ReflectionProperty $property
     * @param Annotation         $annotation
     */
    public function build(ClassMetadata $metadata, ReflectionProperty $property, Annotation $annotation)
    {
        $builder = new ManyToManyAssociationBuilder(
            new ClassMetadataBuilder($metadata),
            [
                'fieldName'    => $property->getName(),
                'targetEntity' => $annotation->targetEntity
            ],
            ClassMetadata::MANY_TO_MANY
        );

        if (isset($annotation->inversedBy) && $annotation->inversedBy) {
            $builder->inversedBy($annotation->inversedBy);
        }

        if (isset($annotation->mappedBy) && $annotation->mappedBy) {
            $builder->mappedBy($annotation->mappedBy);
        }

        $builder->build();
    }
}
