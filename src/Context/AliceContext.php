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
use Hautelook\AliceBundle\Doctrine\Finder\Finder;
use Nelmio\Alice\PersisterInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Context to load fixtures files with Alice loader.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AliceContext implements Context
{
    /**
     * @var Finder
     */
    private $finder;

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
     * @param Finder                           $finder
     * @param LoaderInterface                  $loader
     * @param PersisterInterface|ObjectManager $persister
     */
    function __construct(KernelInterface $kernel, Finder $finder, LoaderInterface $loader, $persister)
    {
        switch (true) {
            case $persister instanceof PersisterInterface:
            case $persister instanceof ObjectManager:
                $this->persister = $persister;
                break;

            default:
                throw new \InvalidArgumentException('Invalid persister type.');
        }

        $this->kernel = $kernel;
        $this->finder = $finder;
        $this->loader = $loader;
    }

    /**
     * @Given /^the fixtures "([^"]*)" are loaded$/
     *
     * @param string $fixturesFile Path to the fixtures
     *
     * @return array
     */
    public function thereAreFixtures($fixturesFile)
    {
        return $this->loader->load(
            $this->persister,
            $this->finder->resolveFixtures($this->kernel, $fixturesFile)
        );
    }
}
