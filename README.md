Install

```
composer require tobur/simple-user v0.2
```

```
SimpleUser\SimpleUserBundle::class => ['all' => true],
```

Change you routing file:

```
simple_user_login:
    resource: '@SimpleUserBundle/Resources/config/routing_login.yaml'
    prefix: /admin/

simple_user_registration:
    resource: '@SimpleUserBundle/Resources/config/routing_registration.yaml'
    prefix: /

simple_user_password_reset:
    resource: '@SimpleUserBundle/Resources/config/routing_password_reset.yaml'
    prefix: /
```

Modify your firewalls:
```
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        admin:
            pattern: ^/admin
            anonymous: ~
            provider: user_provider
            form_login:
                login_path: simple_user_login
                check_path: simple_user_login_check
                default_target_path: '%redirect_after_login%'
                username_parameter: '_username'
                password_parameter: '_password'
                post_only: true
            logout:
                path: simple_user_logout
                target: simple_user_login


    access_control:
        - { path: ^/admin/login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin, roles: ROLE_USER }
        - { path: ^/, roles: IS_AUTHENTICATED_ANONYMOUSLY }
```

Create simple_user.yaml for configure bundle:
```
simple_user:
  user_class: App\Entity\User
  role_class: App\Entity\Role
  firewall_name: admin
  redirect_after_login: '%redirect_after_login%'
  email:
    from: %email_from%
```

Create entity classes:

```
<?php

namespace App\Entity;

use SimpleUser\Model\Role as BaseRole;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role extends BaseRole
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="roles", cascade={"persist","remove"})
     *
     * @var ArrayCollection
     */
    protected $users;

    public function __construct() {
        $this->users = new ArrayCollection();
    }
}
```

```
<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use SimpleUser\Interfaces\SimpleUserRoleInterface;
use SimpleUser\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role", inversedBy="users", cascade={"persist","remove"})
     * @ORM\JoinTable(name="simple_user_to_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     * @var SimpleUserRoleInterface[]
     */
    protected $roles;

    public function __construct() {
        $this->roles = new ArrayCollection();
    }
}

```

Update your database:
```
php bin/console doctrine:schema:update
```

Rewrite templates example:
```
/templates/bundles/SimpleUserBundle/Security/login.html.twig
```

