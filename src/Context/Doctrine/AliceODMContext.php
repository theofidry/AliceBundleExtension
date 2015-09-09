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
class AliceODMContext extends AbstractAliceContext
{
    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->init(
            $kernel,
            $kernel->getContainer()->get('hautelook_alice.doctrine.mongodb.fixtures_finder'),
            $kernel->getContainer()->get('hautelook_alice.fixtures.loader'),
            $this->resolvePersister($kernel->getContainer()->get('doctrine.mongodb.entity_manager'))
        );

        return $this;
    }
}
