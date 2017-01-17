<?php

namespace MMC\FosUserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class MMCFosUserExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $bundles = $container->getParameter('kernel.bundles');

        $serviceId = 'mmc_fos_user_bundle.sonata_admin.user';

        if ($config['admin']['enabled']
            && isset($bundles['MMCSonataAdminBundle'])
            && $container->hasDefinition($serviceId)
        ) {
            $definition = $container->getDefinition($serviceId);

            $definition->addTag('sonata.admin', [
                'manager_type' => 'orm',
                'group' => $config['admin']['group'],
                'label' => 'admin.group.label',
                'icon' => $config['admin']['icon'],
            ]);

            $container->setDefinition($serviceId, $definition);
        }

        $sonataAdminTemplate = 'mmc_fos_user_bundle.sonata_admin.template';

        if ($config['admin']['enabled']
            && isset($bundles['MMCSonataAdminBundle'])
            && $container->hasDefinition($sonataAdminTemplate)
            && $config['admin']['nav_top']
        ) {
            $definition = $container->getDefinition($sonataAdminTemplate);

            $definition->addTag('sonata.block');

            $container->setDefinition($sonataAdminTemplate, $definition);
        }

        if ($config['admin']['enabled']
            && isset($config['admin']['rolesAvailables'])
        ) {
            $container->setParameter('mmc_fos_user_bundle.roles.availables', $config['admin']['rolesAvailables']);
        }
    }

    public function prepend(ContainerBuilder $container)
    {
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        $bundles = $container->getParameter('kernel.bundles');

        if ($config['admin']['enabled']
            && isset($bundles['MMCSonataAdminBundle'])
            && $config['admin']['nav_top']
        ) {
            $sonata_block = [
                'blocks' => [
                    'mmc_fos_user_bundle.sonata_admin.template' => ['context' => ['nav_top']],
                ],
            ];

            $container->prependExtensionConfig('sonata_block', $sonata_block);
        }
    }
}
