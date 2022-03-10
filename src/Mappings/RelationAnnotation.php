<?php

namespace LaravelDoctrine\ACL\Mappings;

abstract class RelationAnnotation implements ConfigAnnotation
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
     *
     * @Enum({"LAZY", "EAGER", "EXTRA_LAZY"})
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
