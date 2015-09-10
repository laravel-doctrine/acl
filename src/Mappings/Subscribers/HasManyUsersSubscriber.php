<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\Builder\ManyToManyAssociationBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use LaravelDoctrine\ACL\Contracts\Organisation;
use LaravelDoctrine\ACL\Mappings\HasManyUsers;
use ReflectionClass;

class HasManyUsersSubscriber implements EventSubscriber
{
    /**
     * @const
     */
    const HAS_MANY_ORGANISATIONS = HasManyUsers::class;

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

        if (!$this->reader || $this->shouldBeOrganisation($metadata)) {
            return;
        }

        foreach ($metadata->getReflectionClass()->getProperties() as $property) {
            $annotation = $this->findUsersAnnotation($property);

            if ($annotation) {
                $builder = new ManyToManyAssociationBuilder(
                    new ClassMetadataBuilder($metadata),
                    [
                        'fieldName'    => $property->getName(),
                        'targetEntity' => $annotation->targetEntity
                    ],
                    ClassMetadata::MANY_TO_MANY
                );

                if ($annotation->inversedBy) {
                    $builder->inversedBy($annotation->inversedBy);
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
    protected function shouldBeOrganisation(ClassMetadata $metadata)
    {
        $reflection = new ReflectionClass($metadata->getName());
        $instance   = $reflection->newInstanceWithoutConstructor();

        return !$instance instanceof Organisation;
    }

    /**
     * @param $property
     *
     * @return HasManyUsers
     */
    protected function findUsersAnnotation($property)
    {
        return $this->reader->getPropertyAnnotation($property, self::HAS_MANY_ORGANISATIONS);
    }
}
