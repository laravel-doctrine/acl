<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisation;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToOneBuilder;
use LaravelDoctrine\ACL\Mappings\ConfigAnnotation;

class BelongsToOrganisationSubscriber extends MappedEventSubscriber
{
    /**
     * @param $metadata
     *
     * @return bool
     */
    protected function shouldBeMapped(ClassMetadata $metadata)
    {
        return $this->getInstance($metadata) instanceof BelongsToOrganisationContract;
    }

    /**
     * @return string
     */
    public function getAnnotationClass()
    {
        return BelongsToOrganisation::class;
    }

    /**
     * @param ConfigAnnotation $annotation
     *
     * @return string
     */
    protected function getBuilder(ConfigAnnotation $annotation)
    {
        return ManyToOneBuilder::class;
    }
}
