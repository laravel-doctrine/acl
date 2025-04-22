# Roles

### Role Entity

To add Roles to your application, you'll have to create a `Role` entity. This entity should implement `LaravelDoctrine\ACL\Contracts\Role`.
Next you should change the class name the `acl.roles.entity` config to your class, by default this is set to `App\Entities\Role`.

```
<?php

namespace App\Entities;

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Contracts\Role as RoleContract;

/**
 * @ORM\Entity()
 */
class Role implements RoleContract
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
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

### A User has Roles

Inside your `User` entity, you have to define the relation with the role. The `User` entity should implement the `LaravelDoctrine\ACL\Contracts\HasRoles` interface.
If you are using annotations, you can use the `@ACL\HasRoles` annotations to define the relations (instead of defining the ManyToMany manually). Import `use LaravelDoctrine\ACL\Mappings as ACL;` in top of the class.

```php
<?php

use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\ACL\Roles\HasRoles;
use LaravelDoctrine\ACL\Mappings as ACL;
use LaravelDoctrine\ACL\Contracts\HasRoles as HasRolesContract;

/**
 * @ORM\Entity()
 */
class User implements HasRolesContract
{
    use HasRoles;
    
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ACL\HasRoles()
     * @var \Doctrine\Common\Collections\ArrayCollection|\LaravelDoctrine\ACL\Contracts\Role[]
     */
    protected $roles;
    
    public function getRoles()
    {
        return $this->roles;
    }
}
```

### Checking if a User has a certain Role

The `LaravelDoctrine\ACL\Roles\HasRoles` trait provides methods to check if the User has a certain Role.

```php
$user->hasRole($role);
$user->hasRoleByName('Super Admin');
```

An array of roles or role names can also checked for.

```php
$user->hasRole([$role1,$role2,$role3]);
$user->hasRoleByName(['User','Admin','Manager']);
```
    
Specifying `true` for the second argument will check that **all** roles are present.

```php
$user->hasRole([$role1,$role2,$role3], true); //User must have all roles
$user->hasRoleByName(['User','Admin','Manager'], true);
```
