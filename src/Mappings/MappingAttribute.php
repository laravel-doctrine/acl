<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Mappings;

use Doctrine\ORM\Mapping\MappingAttribute as DoctrineMappingAttribute;
use Illuminate\Contracts\Config\Repository;

interface MappingAttribute extends DoctrineMappingAttribute
{
    public function getTargetEntity(Repository $config): string|null;
}
