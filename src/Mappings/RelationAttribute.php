<?php

namespace LaravelDoctrine\ACL\Mappings;

use LaravelDoctrine\ACL\Mappings\ConfigAttribute;

abstract class RelationAttribute implements ConfigAttribute
{
    /**
     * @var string
     */
    public $targetEntity;

    /**
     * @var string
     */
    public $mappedBy;

    /**
     * @var array<string>
     */
    public $cascade;

    /**
     * The fetching strategy to use for the association.
     *
     * @var string
     */
    public $fetch = 'LAZY';

    /**
     * @var bool
     */
    public $orphanRemoval = false;

    /**
     * @var string
     */
    public $indexBy;
}