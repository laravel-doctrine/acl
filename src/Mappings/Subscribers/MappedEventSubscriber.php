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
    public function __construct(?Reader $reader, Repository $config)
    {
        $this->reader = $reader;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
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

        if (! $this->reader) {
            return;
        }

        if ($this->isInstantiable($metadata) && $this->shouldBeMapped($metadata)) {
            foreach ($metadata->getReflectionClass()->getProperties() as $property) {
                if ($annotation = $this->findMapping($property)) {
                    $builder = $this->getBuilder($annotation);
                    $builder = new $builder($this->config);
                    $builder->build($metadata, $property, $annotation);
                }
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
     * @return ConfigAnnotation
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

    /**
     * A MappedSuperClass or Abstract class cannot be instantiated.
     *
     * @param ClassMetadata $metadata
     *
     * @return bool
     */
    protected function isInstantiable(ClassMetadata $metadata)
    {
        if ($metadata->isMappedSuperclass) {
            return false;
        }

        if (!$metadata->getReflectionClass() || $metadata->getReflectionClass()->isAbstract()) {
            return false;
        }

        return true;
    }
}
