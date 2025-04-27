<?php

declare(strict_types=1);

namespace LaravelDoctrine\ACL\Organisations;

use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations;
use LaravelDoctrine\ACL\Contracts\Organisation;

use function is_array;

trait BelongsToOrganisation
{
    public function belongsToOrganisation(Organisation|string|array $org, bool $requireAll = false): bool
    {
        if (is_array($org)) {
            foreach ($org as $o) {
                $hasOrganisation = $this->belongsToOrganisation($o);

                if ($hasOrganisation && ! $requireAll) {
                    return true;
                }

                if (! $hasOrganisation && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        }

        if ($this instanceof BelongsToOrganisationContract) {
            if ($this->getOrganisation() && $this->getOrganisationName($org) === $this->getOrganisation()->getName()) {
                return true;
            }
        }

        if ($this instanceof BelongsToOrganisations) {
            foreach ($this->getOrganisations() as $o) {
                if ($this->getOrganisationName($org) === $o->getName()) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function getOrganisationName(Organisation|string $org): string
    {
        return $org instanceof Organisation ? $org->getName() : $org;
    }
}
