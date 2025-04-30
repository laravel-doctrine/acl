<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Attribute;

abstract class RelationAttribute implements MappingAttribute
{
    public string|null $targetEntity;
    public string|null $mappedBy;
    /** @var string[] */
    public array|null $cascade;
    public string $fetch       = 'LAZY';
    public bool $orphanRemoval = false;
    public string|null $indexBy;
}
