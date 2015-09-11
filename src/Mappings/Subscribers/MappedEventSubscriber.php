<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use ReflectionClass;
use ReflectionProperty;

abstract class MappedEventSubscriber implements EventSubscriber
{
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

        if (!$this->reader || $this->shouldBeMapped($metadata)) {
            return;
        }

        foreach ($metadata->getReflectionClass()->getProperties() as $property) {
            if ($annotation = $this->findMapping($property)) {
                $builder = $this->getBuilder();
                $builder = new $builder();
                $builder->build($metadata, $property, $annotation);
            }
        }
    }

    /**
     * @param ClassMetadata $metadata
     *
     * @return bool
     */
    abstract protected function shouldBeMapped(ClassMetadata $metadata);

    /**
     * @return string
     */
    abstract public function getAnnotationClass();

    /**
     * @param $property
     *
     * @return HasManyUsers
     */
    protected function findMapping(ReflectionProperty $property)
    {
        return $this->reader->getPropertyAnnotation($property, $this->getAnnotationClass());
    }

    /**
     * @param ClassMetadata $metadata
     *
     * @return object
     */
    protected function getInstance(ClassMetadata $metadata)
    {
        $reflection = new ReflectionClass($metadata->getName());
        $instance   = $reflection->newInstanceWithoutConstructor();

        return $instance;
    }

    /**
     * @return string
     */
    abstract protected function getBuilder();
}
