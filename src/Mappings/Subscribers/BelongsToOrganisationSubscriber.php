<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Persistence\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisation;
use LaravelDoctrine\ACL\Mappings\Builders\Builder;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToOneBuilder;
use LaravelDoctrine\ACL\Mappings\MappingAttribute;

class BelongsToOrganisationSubscriber extends MappedEventSubscriber
{
    public function getAttributeClass(): string
    {
        return BelongsToOrganisation::class;
    }

    protected function shouldBeMapped(ClassMetadata $metadata): bool
    {
        return $this->getInstance($metadata) instanceof BelongsToOrganisationContract;
    }

    protected function getBuilder(MappingAttribute $attribute): Builder
    {
        return new ManyToOneBuilder($this->config);
    }
}
