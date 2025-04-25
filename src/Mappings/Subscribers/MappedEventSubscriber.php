<?php

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository;
use LaravelDoctrine\ACL\Mappings\Builders\Builder;
use LaravelDoctrine\ACL\Mappings\ConfigAttribute;
use ReflectionClass;
use ReflectionProperty;

abstract class MappedEventSubscriber implements EventSubscriber
{

    /** @return class-string<ConfigAttribute> */
    abstract public function getAttributeClass(): string;

    abstract protected function shouldBeMapped(ClassMetadata $metadata): bool;

    abstract protected function getBuilder(ConfigAttribute $attribute): Builder;

    public function __construct(protected Repository $config)
    {
    }

    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $metadata = $eventArgs->getClassMetadata();

        if ($this->isInstantiable($metadata) && $this->shouldBeMapped($metadata)) {
            foreach ($metadata->getReflectionClass()->getProperties() as $property) {
                foreach ($property->getAttributes($this->getAttributeClass()) as $refAttr) {
                    $attribute = $refAttr->newInstance();
                    $builder = $this->getBuilder($attribute);
                    $builder->build($metadata, $property, $attribute);
                }
            }
        }
    }

    protected function getInstance(ClassMetadata $metadata): object
    {
        $reflection = new ReflectionClass($metadata->getName());
        $instance   = $reflection->newInstanceWithoutConstructor();

        return $instance;
    }

    protected function isInstantiable(ClassMetadata $metadata): bool
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