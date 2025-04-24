<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisation;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToOneBuilder;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;

class BelongsToOrganisationSubscriber extends MappedEventSubscriber
{
    /**
     * @return string
     */
    public function getAttributeClass()
    {
        return BelongsToOrganisation::class;
    }

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
     * @param ConfigAttribute $attribute
     *
     * @return string
     */
    protected function getBuilder(ConfigAttribute $attribute)
    {
        return ManyToOneBuilder::class;
    }
}
