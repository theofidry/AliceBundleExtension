<?php

/*
 * This file is part of the Fidry\AliceBundleExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceBundleExtension\Context\Doctrine;

use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\Common\Persistence\ObjectManager;
use Fidry\AliceBundleExtension\Context\AliceContextInterface;
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
abstract class AbstractAliceContext implements KernelAwareContext, AliceContextInterface
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @param string|null $basePath
     */
    public function __construct($basePath = null)
    {
        $this->basePath = $basePath;
    }

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    final public function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    final public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @Transform /^service$/
     *
     * @throws ServiceNotFoundException
     */
    public function castServiceIdToService($serviceId)
    {
        return $this->kernel->getContainer()->get($serviceId);
    }

    /**
     * @Transform /^persister$/
     *
     * @throws ServiceNotFoundException
     */
    public function castServiceIdToPersister($serviceId)
    {
        $service = $this->castServiceIdToService($serviceId);

        return $this->resolvePersister($service);
    }

    /**
     * {@inheritdoc}
     */
    public function thereAreFixtures($fixturesFile, $persister = null)
    {
        $this->loadFixtures([$fixturesFile], $persister);
    }

    /**
     * {@inheritdoc}
     */
    public function thereAreSeveralFixtures(TableNode $fixturesFileRows, $persister = null)
    {
        $fixturesFiles = [];

        foreach ($fixturesFileRows->getRows() as $fixturesFileRow) {
            $fixturesFiles[] = $fixturesFileRow[0];
        }

        $this->loadFixtures($fixturesFiles, $persister);
    }

    /**
     * @param array              $fixturesFiles
     * @param PersisterInterface $persister
     */
    private function loadFixtures($fixturesFiles, $persister = null)
    {
        if (null === $persister) {
            $persister = $this->getPersister();
        }

        if (true === is_string($persister)) {
            $persister = $this->castServiceIdToPersister($persister);
        }

        $fixtureBundles = [];
        $fixtureDirectories = [];

        foreach ($fixturesFiles as $key => $fixturesFile) {
            if (0 === strpos($fixturesFile, '/')) {
                if (is_dir($fixturesFile)) {
                    $fixtureDirectories[] = $fixturesFile;
                    unset($fixturesFiles[$key]);
                }

                continue;
            }

            if (0 === strpos($fixturesFile, '@')) {
                if (false === strpos($fixturesFile, '.')) {
                    $fixtureBundles[] = $this->kernel->getBundle(substr($fixturesFile, 1));
                    unset($fixturesFiles[$key]);
                }

                continue;
            }

            $fixturesFiles[$key] = sprintf('%s/%s', $this->basePath, $fixturesFile);
        }

        $fixturesFinder = $this->getFixturesFinder();

        if (false === empty($fixtureBundles)) {
            $fixturesFiles = array_merge(
                $fixturesFiles,
                $fixturesFinder->getFixtures($this->kernel, $fixtureBundles, $this->kernel->getEnvironment())
            );
        }

        if (false === empty($fixtureDirectories)) {
            $fixturesFiles = array_merge(
                $fixturesFiles,
                $fixturesFinder->getFixturesFromDirectory($fixtureDirectories)
            );
        }

        $this->getLoader()->load(
            $persister,
            $fixturesFinder->resolveFixtures($this->kernel, $fixturesFiles)
        );
    }

    /**
     * @param Doctrine|PersisterInterface|null $persister
     *
     * @return PersisterInterface
     *
     * @throws \InvalidArgumentException
     */
    final protected function resolvePersister($persister)
    {
        if (null === $persister) {
            return $this->getPersister();
        }

        switch (true) {
            case $persister instanceof PersisterInterface:
                return $persister;
            case $persister instanceof ObjectManager:
                return new Doctrine($persister);

            default:
                throw new \InvalidArgumentException(sprintf(
                    'Invalid persister type, expected Nelmio\Alice\PersisterInterface or Doctrine\Common\Persistence\ObjectManager. Got %s instead.',
                    get_class($persister)
                ));
        }
    }

    /**
     * @return LoaderInterface
     */
    protected function getLoader()
    {
        return $this->kernel->getContainer()->get('hautelook_alice.fixtures.loader');
    }

    /**
     * @return PersisterInterface
     */
    abstract protected function getPersister();

    /**
     * @return FixturesFinderInterface
     */
    abstract protected function getFixturesFinder();
}
