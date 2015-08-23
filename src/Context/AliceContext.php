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
use Nelmio\Alice\PersisterInterface;

/**
 * Context to load fixtures files with Alice loader.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AliceContext implements Context
{
    /**
     * @var LoaderInterface
     */
    protected $loader;

    /**
     * @param PersisterInterface|ObjectManager $persister
     * @param LoaderInterface    $loader
     */
    function __construct($persister, LoaderInterface $loader)
    {
        switch (true) {
            case $persister instanceof PersisterInterface:
            case $persister instanceof ObjectManager:
                $this->persister = $persister;
                break;

            default:
                throw new \InvalidArgumentException('Invalid persister type.');
        }

        $this->loader = $loader;
    }

    /**
     * @Given /^the fixtures "([^"]*)" are loaded$/
     *
     * @param string $fixtures Path to the fixtures
     *
     * @return array
     */
    public function thereAreFixtures($fixtures)
    {
        return $this->loader->load($this->persister, $fixtures);
    }
}
