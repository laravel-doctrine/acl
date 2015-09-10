<?php

namespace LaravelDoctrine\ACL\Mappings;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("PROPERTY")
 */
final class BelongsToOrganisations extends Annotation
{
    /**
     * @var string
     */
    public $targetEntity = 'Organisation';

    /**
     * @var string
     */
    public $mappedBy = 'users';

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
