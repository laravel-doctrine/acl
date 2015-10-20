<?php

/**
 * Created by IntelliJ IDEA.
 * User: mduncan
 * Date: 10/16/15
 * Time: 10:29 AM
 */
namespace LaravelDoctrine\ACL\Organisations;

use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation as BelongsToOrganisationContract;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations as BelongsToOrganisationsContract;
use LaravelDoctrine\ACL\Contracts\Organisation as OrganisationContract;

trait BelongsToOrganisation
{
    /**
     * @param  OrganisationContract|string|array $org
     * @param  bool                              $requireAll
     * @return bool
     */
    public function belongsToOrganisation($org, $requireAll = false)
    {
        if (is_array($org)) {
            foreach ($org as $o) {
                $hasOrganisation = $this->belongsToOrganisation($o);

                if ($hasOrganisation && !$requireAll) {
                    return true;
                } elseif (!$hasOrganisation && $requireAll) {
                    return false;
                }
            }

            return $requireAll;
        } else {
            if ($this instanceof BelongsToOrganisationContract) {
                if (!is_null($this->getOrganisation()) && $this->getOrganisationName($org) === $this->getOrganisation()->getName()) {
                    return true;
                }
            }
            if ($this instanceof BelongsToOrganisationsContract) {
                foreach ($this->getOrganisations() as $o) {
                    if ($this->getOrganisationName($org) === $o->getName()) {
                        return true;
                    }
                }
            }

            return false;
        }
    }

    /**
     * @param OrganisationContract|string $org
     *
     * @return string
     */
    protected function getOrganisationName($org)
    {
        return $org instanceof OrganisationContract ? $org->getName() : $org;
    }
}
