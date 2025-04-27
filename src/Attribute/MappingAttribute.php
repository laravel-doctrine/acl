<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Attribute;

use Doctrine\ORM\Mapping\MappingAttribute as DoctrineMappingAttribute;
use Illuminate\Contracts\Config\Repository as Config;

interface MappingAttribute extends DoctrineMappingAttribute
{
    public function getTargetEntity(Config $config): string|null;
}
