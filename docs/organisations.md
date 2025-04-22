# Organisations

A lot of applications have an organisations structure. Teams, Organisations, Offices, ... To add this functionality to your Application, you will have
to create an entity that implements `LaravelDoctrine\ACL\Contracts\Organisation`. Next change `acl.organisations.entity` to your entity.

```php
<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Organisation;
use LaravelDoctrine\ACL\Mappings as ACL;

/**
 * @ORM\Entity
 */
class Team implements Organisation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
```

### User can belong to one organisation

The User class should implement `LaravelDoctrine\ACL\Contracts\BelongsToOrganisation`. You can use the `@ACL\BelongsToOrganisation` annotation to define the relation.

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisation;

/**
 * @ORM\Entity
 */
class User implements BelongsToOrganisation
{
    /**
     * @ACL\BelongsToOrganisation
     * @var Organisation
     */
    protected $organisation;
    
    /**
     * @return Organisation
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }
}
```

### User can belong to multiple organisations

The User class should implement `LaravelDoctrine\ACL\Contracts\BelongsToOrganisations`. You can use the `@ACL\BelongsToOrganisations` annotation to define the relation.

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Contracts\BelongsToOrganisations;

/**
 * @ORM\Entity
 */
class User implements BelongsToOrganisations
{
    /**
     * @ACL\BelongsToOrganisations
     * @var Organisation[]
     */
    protected $organisations;
    
    /**
     * @return Organisation[]
     */
    public function getOrganisations()
    {
        return $this->organisations;
    }
}
```

### Checking if a User has a certain Organisation

The `LaravelDoctrine\ACL\Organisations\BelongsToOrganisation` trait provides methods to check if the User has a certain Organisation.

```php
$user->belongsToOrganisation($org);
```

An array of Organisations or Organisation names can also be checked for.

```php
$user->belongsToOrganisation([$org1,$org2,$org3]);
$user->belongsToOrganisation(['Company 1','Company 2','Company 3']);
```
    
Specifying `true` for the second argument will check that **all** roles are present.

```php
$user->belongsToOrganisation([$org1,$org2,$org3], true); //User must belong to all three organisations to return true
$user->belongsToOrganisation(['Company 1','Company 2','Company 3'], true);
```
