# MMC FOS UserBundle

Implementation of FosUserBundle for MMC

## Installation

Add the repositorie in composer.json
```json
"repositories" : [
    {
        "type" : "vcs",
        "url" : "git@git.meuhmeuhconcept.fr:mmc/FosUserBundle.git"
    }
],
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

### Configure bundles

In app/config/config.yml
```yaml
fos_user:
    db_driver: orm # other valid values are 'mongodb', 'couchdb' and 'propel'
    firewall_name: main
    user_class: MMC\FosUserBundle\Entity\User


twig:
    globals:
        mmc_fos_user_layout: "FOSUserBundle::layout.html.twig"
```
