<?php

namespace LaravelDoctrine\ACL\Mappings;

use LaravelDoctrine\ACL\Mappings\ConfigAttribute;

abstract class RelationAttribute implements ConfigAttribute
{
    public ?string $targetEntity;
    public ?string $mappedBy;

    /** @var string[] */
    public ?array $cascade;

    public string $fetch = 'LAZY';

    public bool $orphanRemoval = false;

    public ?string $indexBy;
}