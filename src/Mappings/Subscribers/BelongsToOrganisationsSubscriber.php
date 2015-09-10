<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\ManyToManyAssociationBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations as BelongsToOrganisationsContract;
use LaravelDoctrine\ACL\Mappings\BelongsToOrganisations;
use ReflectionClass;

class BelongsToOrganisationsSubscriber implements EventSubscriber
{
    /**
     * @const
     */
    const ANNOTATION = BelongsToOrganisations::class;

    /**
     * @var Reader|null
     */
    protected $reader;

    /**
     * @param Reader|null $reader
     */
    public function __construct(Reader $reader = null)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();

        if (!$this->reader || $this->shouldBelongToOrganisation($metadata)) {
            return;
        }

        foreach ($metadata->getReflectionClass()->getProperties() as $property) {
            $annotation = $this->findOrganisationsAnnotation($property);

            if ($annotation) {
                $builder = new ManyToManyAssociationBuilder(
                    new ClassMetadataBuilder($metadata),
                    [
                        'fieldName'    => $property->getName(),
                        'targetEntity' => $annotation->targetEntity
                    ],
                    ClassMetadata::MANY_TO_MANY
                );

                if ($annotation->mappedBy) {
                    $builder->mappedBy($annotation->mappedBy);
                }

                $builder->build();
            }
        }
    }

    /**
     * @param $metadata
     *
     * @return bool
     */
    protected function shouldBelongToOrganisation(ClassMetadata $metadata)
    {
        $reflection = new ReflectionClass($metadata->getName());
        $instance   = $reflection->newInstanceWithoutConstructor();

        return !$instance instanceof BelongsToOrganisationsContract;
    }

    /**
     * @param $property
     *
     * @return BelongsToOrganisations
     */
    protected function findOrganisationsAnnotation($property)
    {
        return $this->reader->getPropertyAnnotation($property, self::ANNOTATION);
    }
}
