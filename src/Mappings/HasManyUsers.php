<?php

namespace LaravelDoctrine\ACL\Mappings;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class HasManyUsers extends Annotation
{
    /**
     * @var string
     */
    public $targetEntity = 'User';

    /**
     * @var string
     */
    public $inversedBy = 'organisations';

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
