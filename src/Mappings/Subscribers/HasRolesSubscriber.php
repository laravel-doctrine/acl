<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Persistence\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Mappings\Builders\Builder;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;
use LaravelDoctrine\ACL\Mappings\HasRoles;
use LaravelDoctrine\ACL\Mappings\MappingAttribute;

class HasRolesSubscriber extends MappedEventSubscriber
{
    public function getAttributeClass(): string
    {
        return HasRoles::class;
    }

    protected function shouldBeMapped(ClassMetadata $metadata): bool
    {
        return $this->getInstance($metadata) instanceof HasRolesContract;
    }

    protected function getBuilder(MappingAttribute $attribute): Builder
    {
        return new ManyToManyBuilder($this->config);
    }
}
