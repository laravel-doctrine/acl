<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings\Subscribers;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Illuminate\Contracts\Config\Repository as Config;
use LaravelDoctrine\ACL\Attribute\MappingAttribute;
use LaravelDoctrine\ACL\Mappings\Builders\Builder;
use ReflectionClass;

abstract class MappedEventSubscriber implements EventSubscriber
{
    /** @return class-string<MappingAttribute> */
    abstract public function getAttributeClass(): string;

    abstract protected function shouldBeMapped(ClassMetadata $metadata): bool;

    abstract protected function getBuilder(MappingAttribute $attribute): Builder;

    public function __construct(protected Config $config)
    {
    }

    /** @return array<int, string> */
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $metadata = $eventArgs->getClassMetadata();

        if (! $this->isInstantiable($metadata) || ! $this->shouldBeMapped($metadata)) {
            return;
        }

        foreach ($metadata->getReflectionClass()->getProperties() as $property) {
            foreach ($property->getAttributes($this->getAttributeClass()) as $refAttr) {
                $attribute = $refAttr->newInstance();
                $builder   = $this->getBuilder($attribute);
                $builder->build($metadata, $property, $attribute);
            }
        }
    }

    protected function getInstance(ClassMetadata $metadata): object
    {
        $reflection = new ReflectionClass($metadata->getName());

        return $reflection->newInstanceWithoutConstructor();
    }

    protected function isInstantiable(ClassMetadata $metadata): bool
    {
        if ($metadata->isMappedSuperclass) {
            return false;
        }

        return $metadata->getReflectionClass() && ! $metadata->getReflectionClass()->isAbstract();
    }
}
