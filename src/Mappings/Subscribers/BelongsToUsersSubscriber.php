<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;
use LaravelDoctrine\ACL\Mappings\BelongsToUsers;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;

class BelongsToUsersSubscriber extends MappedEventSubscriber
{
    /**
     * @param ClassMetadata $metadata
     *
     * @return bool
     */
    protected function shouldBeMapped(ClassMetadata $metadata)
    {
        return !$this->getInstance($metadata) instanceof RoleContract;
    }

    /**
     * @return string
     */
    public function getAnnotationClass()
    {
        return BelongsToUsers::class;
    }

    /**
     * @return string
     */
    protected function getBuilder()
    {
        return ManyToManyBuilder::class;
    }
}
