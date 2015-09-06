<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension\Context;

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ObjectManager;
use Hautelook\AliceBundle\Alice\DataFixtures\LoaderInterface;
use Hautelook\AliceBundle\Finder\FixturesFinderInterface;
use Nelmio\Alice\Persister\Doctrine;
use Nelmio\Alice\PersisterInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Context to load fixtures files with Alice loader.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AliceContext implements Context
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var FixturesFinderInterface
     */
    private $fixturesFinder;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @param KernelInterface                  $kernel
     * @param FixturesFinderInterface          $fixturesFinder
     * @param LoaderInterface                  $loader
     * @param PersisterInterface|ObjectManager $persister
     * @param string                           $basePath
     */
    function __construct(
        KernelInterface $kernel,
        FixturesFinderInterface $fixturesFinder,
        LoaderInterface $loader,
        $persister,
        $basePath = null
    ) {
        $this->kernel = $kernel;
        $this->fixturesFinder = $fixturesFinder;
        $this->loader = $loader;
        $this->persister = $this->resolvePersister($persister);
        $this->basePath = $basePath;
    }

    /**
     * @Transform /^(\d+)$/
     *
     * @throws ServiceNotFoundException
     */
    public function castServiceIdToService($serviceId)
    {
        return $this->kernel->getContainer()->get($serviceId);
    }

    /**
     * @Transform /^(\d+)$/
     *
     * @throws ServiceNotFoundException
     */
    public function castPersistence($serviceId)
    {
        $service = $this->castServiceIdToService($serviceId);

        return $this->resolvePersister($service);
    }

    /**
     * @Given the fixtures :fixturesFile are loaded
     * @Given the fixtures :fixturesFile are loaded with the persister :persister
     *
     * @param string $fixturesFile Path to the fixtures
     * @param string $persister
     *
     * @return array
     */
    public function thereAreFixtures($fixturesFile, $persister = null)
    {
        if (0 !== strpos($fixturesFile, '/') && 0 !== strpos($fixturesFile, '@')) {
            $fixturesFile = sprintf('%s/%s', $this->basePath, $fixturesFile);
        }

        return $this->loader->load(
            $this->resolvePersister($persister),
            $this->fixturesFinder->resolveFixtures($this->kernel, [$fixturesFile])
        );
    }

    /**
     * @param $persister
     *
     * @return Doctrine|PersisterInterface|null
     *
     * @throws \InvalidArgumentException
     */
    private function resolvePersister($persister)
    {
        $_persister = null;

        if (null === $persister) {
            return $this->persister;
        }

        switch (true) {
            case $persister instanceof PersisterInterface:
                $_persister = $persister;
                break;
            case $persister instanceof ObjectManager:
                $_persister = new Doctrine($persister);
                break;

            default:
                throw new \InvalidArgumentException(sprintf(
                    'Invalid persister type, expected Nelmio\Alice\PersisterInterface or Doctrine\Common\Persistence\ObjectManager. Got %s instead.',
                    get_class($persister)
                ));
        }

        return $_persister;
    }
}
