<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension\Context\Doctrine;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Context to load fixtures files with Alice loader.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AlicePHPCRContext extends AbstractAliceContextInterface
{
    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->init(
            $kernel,
            $kernel->getContainer()->get('hautelook_alice.doctrine.phpcr.fixtures_finder'),
            $kernel->getContainer()->get('hautelook_alice.fixtures.loader'),
            $this->resolvePersister($kernel->getContainer()->get('doctrine.phpcr.entity_manager'))
        );

        return $this;
    }

    /**
     * @BeforeScenario @createSchema
     */
    public function createDatabase()
    {
        // TODO: Implement createDatabase() method.
    }

    /**
     * @BeforeScenario @dropSchema
     */
    public function dropDatabase()
    {
        // TODO: Implement dropDatabase() method.
    }

    /**
     * @Given the database is empty
     */
    public function emptyDatabase()
    {
        // TODO: Implement emptyDatabase() method.
    }
}
