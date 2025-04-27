<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Persistence\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Attribute\BelongsToOrganisations;
use LaravelDoctrine\ACL\Attribute\MappingAttribute;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations as BelongsToOrganisationsContract;
use LaravelDoctrine\ACL\Mappings\Builders\Builder;
use LaravelDoctrine\ACL\Mappings\Builders\ManyToManyBuilder;

class BelongsToOrganisationsSubscriber extends MappedEventSubscriber
{
    public function getAttributeClass(): string
    {
        return BelongsToOrganisations::class;
    }

    protected function shouldBeMapped(ClassMetadata $metadata): bool
    {
        return $this->getInstance($metadata) instanceof BelongsToOrganisationsContract;
    }

    protected function getBuilder(MappingAttribute $attribute): Builder
    {
        return new ManyToManyBuilder($this->config);
    }
}
