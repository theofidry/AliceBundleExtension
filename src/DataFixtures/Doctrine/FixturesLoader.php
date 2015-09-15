<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension\DataFixtures\Doctrine;

use Doctrine\Common\Persistence\ObjectManager;
use Fidry\AliceFixturesExtension\DataFixtures\Doctrine\FixturesLoaderInterface as AliceFixturesExtensionFixturesLoaderInterface;
use Hautelook\AliceBundle\Alice\DataFixtures\Fixtures\LoaderInterface as FixturesLoaderInterface;
use Hautelook\AliceBundle\Alice\DataFixtures\LoaderInterface;
use Hautelook\AliceBundle\Doctrine\DataFixtures\Executor\FixturesExecutorInterface;
use Hautelook\AliceBundle\Doctrine\Finder\FixturesFinder;
use Hautelook\AliceBundle\Doctrine\Generator\LoaderGeneratorInterface;
use Hautelook\AliceBundle\Finder\FixturesFinderInterface;
use Hautelook\AliceBundle\Resolver\BundlesResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class FixturesLoader implements AliceFixturesExtensionFixturesLoaderInterface
{
    /**
     * @var FixturesExecutorInterface
     */
    private $fixturesExecutor;

    /**
     * @var FixturesFinderInterface|FixturesFinder
     */
    private $fixturesFinder;

    /**
     * @var FixturesLoaderInterface
     */
    private $fixturesLoader;

    /**
     * @var LoaderInterface
     */
    private $loader;

    /**
     * @var LoaderGeneratorInterface
     */
    private $loaderGenerator;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @param KernelInterface           $kernel
     * @param ObjectManager             $objectManager
     * @param LoaderInterface           $loader
     * @param FixturesLoaderInterface   $fixturesLoader
     * @param FixturesFinderInterface   $fixturesFinder
     * @param BundlesResolverInterface  $bundlesResolver
     * @param LoaderGeneratorInterface  $loaderGenerator
     * @param FixturesExecutorInterface $fixturesExecutor
     */
    public function __construct(
        KernelInterface $kernel,
        ObjectManager $objectManager,
        LoaderInterface $loader,
        FixturesLoaderInterface $fixturesLoader,
        FixturesFinderInterface $fixturesFinder,
        BundlesResolverInterface $bundlesResolver,
        LoaderGeneratorInterface $loaderGenerator,
        FixturesExecutorInterface $fixturesExecutor
    ) {
        $this->kernel = $kernel;
        $this->objectManager = $objectManager;
        $this->loader = $loader;
        $this->fixturesLoader = $fixturesLoader;
        $this->fixturesFinder = $fixturesFinder;
        $this->loaderGenerator = $loaderGenerator;
        $this->fixturesExecutor = $fixturesExecutor;
    }

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $environment = 'test';
        $bundles = $this->kernel->getBundles();

        $fixtures = $this->fixturesFinder->getFixtures($this->kernel, $bundles, $environment);

        //TODO: remove logger
        $this->fixturesExecutor->execute(
            $this->objectManager,
            $this->loaderGenerator->generate($this->loader, $this->fixturesLoader, $bundles, $environment),
            $fixtures,
            false,
            function () {}
        );
    }

    /**
     * {@inheritdoc}
     */
    public function reload()
    {
        //TODO: empty database
        $this->load();
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $this->objectManager->flush();
        $this->objectManager->clear();
    }
}
