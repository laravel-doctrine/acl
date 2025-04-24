<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionClass;
use ReflectionProperty;

abstract class MappedEventSubscriber implements EventSubscriber
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @param Repository  $config
     */
    public function __construct(Repository $config)
    {
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

        if ($this->isInstantiable($metadata) && $this->shouldBeMapped($metadata)) {
            foreach ($metadata->getReflectionClass()->getProperties() as $property) {
                if ($attribute = $this->findMapping($property)) {
                    $builder = $this->getBuilder($attribute);
                    $builder = new $builder($this->config);
                    $builder->build($metadata, $property, $attribute);
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
    abstract public function getAttributeClass();

    /**
     * @param $property
     *
     * @return ConfigAttribute|null
     */
    protected function findMapping(ReflectionProperty $property)
    {
        $attributes = $property->getAttributes($this->getAttributeClass());
        if (count($attributes) > 0) {
            return $attributes[0]->newInstance();
        }
        
        return null;
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
     * @param ConfigAttribute $attribute
     *
     * @return string
     */
    abstract protected function getBuilder(ConfigAttribute $attribute);

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