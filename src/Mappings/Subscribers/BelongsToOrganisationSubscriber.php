<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisation;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToOneBuilder;

class BelongsToOrganisationSubscriber extends MappedEventSubscriber
{
    /**
     * @param $metadata
     *
     * @return bool
     */
    protected function shouldBeMapped(ClassMetadata $metadata)
    {
        return !$this->getInstance($metadata) instanceof BelongsToOrganisationContract;
    }

    /**
     * @return string
     */
    public function getAnnotationClass()
    {
        return BelongsToOrganisation::class;
    }

    /**
     * @return string
     */
    protected function getBuilder()
    {
        return ManyToOneBuilder::class;
    }
}
