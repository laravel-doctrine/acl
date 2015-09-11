<?php

namespace LaravelDoctrine\ACL\Mappings\Builders;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\ORM\Mapping\ClassMetadata;

interface Builder
{
    /**
     * @param ClassMetadata       $metadata
     * @param \ReflectionProperty $property
     * @param Annotation          $annotation
     */
    public function build(ClassMetadata $metadata, \ReflectionProperty $property, Annotation $annotation);
}
