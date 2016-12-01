# MMC FOS UserBundle

Implementation of FosUserBundle for MMC

## Installation

Add the repository in composer.json
```json
{
    /* ..... */

    "repositories" : [
        {
            "type" : "vcs",
            "url" : "git@git.meuhmeuhconcept.fr:mmc/FosUserBundle.git"
        }
    ],

    "require": {
        // ...

        "friendsofsymfony/user-bundle": "~2.0@dev",

        // ...
    },

    /* ..... */
}
```

Via composer
```bash
composer require mmc/fos-user-bundle
```
Installs bundles web assets under a public web directory
```bash
bin/console assets:install
```
## Configuration

### Add bundles
In app/AppKernel.php, add following lines
```php
public function registerBundles()
{
    $bundles = [

        // ...

        new MMC\FosUserBundle\MMCFosUserBundle(),
        new FOS\UserBundle\FOSUserBundle(),

        // ...
    ];

    // ...
}
```

Add fos user configuration and twig layout :
```yaml
# app/config/config.yml
fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    user_class: MMC\FosUserBundle\Entity\User


twig:
    globals:
        mmc_fos_user_layout: "FOSUserBundle::layout.html.twig"
```
If you need a design layout, you should use the default layout :
```yaml
# app/config/config.yml

twig:
    globals:
        mmc_fos_user_layout: "FOSUserBundle:Default:layout.html.twig"
```
Add fos user security configuration :
```yaml
# app/config/security.yml
    encoders:
        FOS\UserBundle\Model\UserInterface: bcrypt

    role_hierarchy:
        ROLE_ADMIN:       ROLE_USER
        ROLE_SUPER_ADMIN: ROLE_ADMIN

    providers:
        mmc_fos_user:
            id: fos_user.user_provider.username_email

    firewalls:
        main:
            pattern: ^/
            form_login:
                provider: mmc_fos_user
                csrf_token_generator: security.csrf.token_manager
                default_target_path:     /admin
            logout:
                path:     /logout
                target:   /login
            anonymous:    true
            remember_me:
                secret:   '%secret%'
                lifetime: 604800 # 1 week in seconds
                path:     /

    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/register, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/admin/, role: ROLE_ADMIN }
```

Add fos user route :
```yaml
# app/config/routing.yml
fos_user:
    resource: "@FOSUserBundle/Resources/config/routing/all.xml"
```

## Customization

If you need to change the logout path ('/logout' by default), you should edit the twig global 'mmc_fos_user_bundle_logout_path'.
For example, if I need '/admin/logout' for logout path :

```yaml
# app/config/config.yml

twig:
    globals:
        mmc_fos_user_bundle_logout_path: '/admin/logout'
```

And change the path in security.yml :

```yaml
# app/config/security.yml

    /----

    main:
        logout:
            path:     /admin/logout
            target:   /admin/login
    /----
```

## Use with MMC/SonataAdminBundle


If you use the bundle MMCSonataAdminBudnle and you need to use the admin of users you can enable it like this:

```yaml
# app/config/config.yml
mmc_fos_user:
    admin: ~
```

By default, the admin is place under `sonata.admin.group.administration`, you can change it like this :
```yaml
# app/config/config.yml
mmc_fos_user:
    admin:
        group: 'name.of.my.custom.group'
        icon: '<i class="fa fa-user"></i>'
        nav_top: ~
```

If you override the configuration on `sonata_admin.dashboard.groups`, the previous configuration is useless because it will be overwritten.

You should add the service id `mmc_fos_user_bundle.sonata_admin.user` in the items list :
```yaml
# app/config/config.yml
sonata_admin:
    dashboard:
        groups:
            sonata.admin.group.myGroup:
                items:
                    - my_first.admin
                    - mmc_fos_user_bundle.sonata_admin.user
```
## Create a custom block

If you need to add a new custom block, you should :

-  [Create a block service](https://sonata-project.org/bundles/block/master/doc/reference/your_first_block.html)
