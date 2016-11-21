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
    /* ..... */
}
```

Via composer
```bash
composer require mmc/fos-user-bundle
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

## Configure bundles

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
Add fos user security configuration :
```yaml
# app/config/security.yml
    encoders:
        FOS\UserBundle\Model\UserInterface: bcrypt

    providers:
        mmc_fos_user:
            id: fos_user.user_provider.username_email

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
```
Add fos user route :
```yaml
# app/config/routing.yml
fos_user:
    resource: "@FOSUserBundle/Resources/config/routing/all.xml"
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
    admin: ~
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
