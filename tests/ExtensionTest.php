<?php

namespace Fidry\AliceFixturesExtension\Tests;

use Fidry\AliceFixturesExtension\Extension;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Definition;

/**
 * @coversDefaultClass Fidry\AliceFixturesExtension\Extension
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class ExtensionTest extends PHPUnit_Framework_TestCase
{
    private static $defaultConfig = [
        'fixtures_base_path' => null,
        'db_drivers'         => [
            'orm'     => null,
            'mongodb' => null,
            'phpcr'   => null,
        ],
        'lifetime'           => null,
    ];

    public function testConstructor()
    {
        new Extension();
    }

    public function testLoad()
    {
        $containerBuilderProphecy = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');

        $containerBuilderProphecy->hasExtension('http://symfony.com/schema/dic/services')->shouldBeCalled();
        $containerBuilderProphecy->getParameter('paths.base')->willReturn('basePath');

        $containerBuilderProphecy
            ->setParameter(
                'behat.alice_fixtures_extension.fixtures_base_path',
                'basePath/features/fixtures'
            )
            ->shouldBeCalled()
        ;
        $containerBuilderProphecy
            ->setParameter(
                'behat.alice_fixtures_extension.db_drivers',
                self::$defaultConfig['db_drivers']
            )
            ->shouldBeCalled()
        ;
        $containerBuilderProphecy
            ->setParameter(
                'behat.alice_fixtures_extension.lifetime',
                self::$defaultConfig['lifetime']
            )
            ->shouldBeCalled()
        ;

        $containerBuilderProphecy
            ->setDefinition(
                'behat.alice_fixtures_extension.alice_context_initializer',
                $this->definition('Fidry\AliceFixturesExtension\Context\Initializer\AliceContextInitializer')
            )
            ->shouldBeCalled()
        ;

        $containerBuilderProphecy
            ->addResource($this->service(getcwd().'/src/Resources/config/services.xml'))
            ->shouldBeCalled()
        ;

        $extension = new Extension();

        $extension->load($containerBuilderProphecy->reveal(), self::$defaultConfig);
    }

    /**
     * Checks that the argument passed is an instance of Definition for the given class.
     *
     * @param string $class FQCN
     *
     * @return \Prophecy\Argument\Token\CallbackToken
     */
    public function definition($class)
    {
        return \Prophecy\Argument::that(function ($args) use ($class) {
            /** @var Definition $args */
            if (false === $args instanceof Definition) {
                return false;
            }
            $service = (new \ReflectionClass($args->getClass()))->newInstanceWithoutConstructor();
            return $service instanceof $class;
        });
    }

    /**
     * Checks that the argument passed is an instance of FileResource with the given resource.
     *
     * @param string $filePath
     *
     * @return \Prophecy\Argument\Token\CallbackToken
     */
    private function service($filePath)
    {
        return \Prophecy\Argument::that(function ($args) use ($filePath) {
            /** @var FileResource $args */
            if (false === $args instanceof FileResource) {
                return false;
            }
            return $filePath === $args->getResource();
        });
    }
}
