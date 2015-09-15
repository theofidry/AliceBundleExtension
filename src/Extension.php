<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Hautelook\AliceBundle\DependencyInjection\Configuration as HautelookAliceBundleConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class Extension implements ExtensionInterface
{
    const LIFETIME_FEATURE = 'feature';
    const LIFETIME_SCENARIO = 'scenario';

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'alice_fixtures_extension';
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('fixtures_base_path')
                    ->defaultValue(null)
                ->end()
                ->arrayNode('db_drivers')
                    ->info('The list of enabled drivers.')
                    ->addDefaultsIfNotSet()
                    ->cannotBeOverwritten()
                    ->children()
                        ->booleanNode(HautelookAliceBundleConfiguration::ORM_DRIVER)
                            ->defaultValue(null)
                        ->end()
                        ->booleanNode(HautelookAliceBundleConfiguration::MONGODB_DRIVER)
                            ->defaultValue(null)
                        ->end()
                            ->booleanNode(HautelookAliceBundleConfiguration::PHPCR_DRIVER)
                                ->defaultValue(null)
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('lifetime')
                    ->defaultValue(null)
                    ->validate()
                        ->ifNotInArray([self::LIFETIME_FEATURE, self::LIFETIME_SCENARIO, null])
                        ->thenInvalid('Invalid fixtures lifetime "%s"')
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container Behat container, does not contains the definitions of the Symfony application.
     * @param array            $config    Extension configuration.
     */
    public function load(ContainerBuilder $container, array $config)
    {
        if (null === $config['fixtures_base_path']) {
            $config['fixtures_base_path'] = sprintf('%s/features/fixtures', $container->getParameter('paths.base'));
        }

        foreach ($config as $key => $value) {
            $container->setParameter(sprintf('behat.%s.%s', $this->getConfigKey(), $key), $value);
        }

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }
}
