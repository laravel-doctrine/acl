<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\HasPermissions as HasPermissionsContract;
use LaravelDoctrine\ACL\Mappings\Builders\Builder;
use LaravelDoctrine\ACL\Mappings\Builders\JsonArrayBuilder;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use LaravelDoctrine\ACL\Mappings\HasPermissions;

class HasPermissionsSubscriber extends MappedEventSubscriber
{
    protected function shouldBeMapped(ClassMetadata $metadata): bool
    {
        return $this->getInstance($metadata) instanceof HasPermissionsContract;
    }

    public function getAttributeClass(): string
    {
        return HasPermissions::class;
    }

    protected function getBuilder(ConfigAttribute $attribute): Builder
    {
        // If there's a target entity, create pivot table
        if ($attribute->getTargetEntity($this->config)) {
            return new ManyToManyBuilder($this->config);
        }

        // Else save the permissions inside the table as json
        return new JsonArrayBuilder($this->config);
    }
}
