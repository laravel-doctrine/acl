<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations as BelongsToOrganisationsContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisations;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;

class BelongsToOrganisationsSubscriber extends MappedEventSubscriber
{
    /**
     * @param $metadata
     *
     * @return bool
     */
    protected function shouldBeMapped(ClassMetadata $metadata)
    {
        return !$this->getInstance($metadata) instanceof BelongsToOrganisationsContract;
    }

    /**
     * @return string
     */
    public function getAnnotationClass()
    {
        return BelongsToOrganisations::class;
    }

    /**
     * @return string
     */
    protected function getBuilder()
    {
        return ManyToManyBuilder::class;
    }
}
