<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations as BelongsToOrganisationsContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisations;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;

class BelongsToOrganisationsSubscriber extends MappedEventSubscriber
{
    /**
     * @return string
     */
    public function getAttributeClass()
    {
        return BelongsToOrganisations::class;
    }

    /**
     * @param $metadata
     *
     * @return bool
     */
    protected function shouldBeMapped(ClassMetadata $metadata)
    {
        return $this->getInstance($metadata) instanceof BelongsToOrganisationsContract;
    }

    /**
     * @param ConfigAttribute $attribute
     *
     * @return string
     */
    protected function getBuilder(ConfigAttribute $attribute)
    {
        return ManyToManyBuilder::class;
    }
}
