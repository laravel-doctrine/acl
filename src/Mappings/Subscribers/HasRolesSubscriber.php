<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;
use LaravelDoctrine\ACL\Mappings\Builders\Builder;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use LaravelDoctrine\ACL\Mappings\HasRoles;

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

    protected function getBuilder(ConfigAttribute $attribute): Builder
    {
        return new ManyToManyBuilder($this->config);
    }
}
