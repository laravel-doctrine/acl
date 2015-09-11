<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\ConfigAnnotation;
use ReflectionClass;
use ReflectionProperty;

abstract class MappedEventSubscriber implements EventSubscriber
{
    /**
     * @var Reader|null
     */
    protected $reader;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @param Reader|null $reader
     * @param Repository  $config
     */
    public function __construct(Reader $reader = null, Repository $config)
    {
        $this->reader = $reader;
        $this->config = $config;
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
                $builder = $this->getBuilder($annotation);
                $builder = new $builder($this->config);
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
     * @param ConfigAnnotation $annotation
     *
     * @return string
     */
    abstract protected function getBuilder(ConfigAnnotation $annotation);
}
