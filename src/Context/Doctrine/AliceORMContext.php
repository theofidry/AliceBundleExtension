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

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Context to load fixtures files with Alice loader.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AliceORMContext extends AbstractAliceContext
{
    /**
     * @var SchemaTool
     */
    private $schemaTool;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->init(
            $kernel,
            $kernel->getContainer()->get('hautelook_alice.doctrine.orm.fixtures_finder'),
            $kernel->getContainer()->get('hautelook_alice.fixtures.loader'),
            $this->resolvePersister($kernel->getContainer()->get('doctrine.orm.entity_manager'))
        );

        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');

        $this->schemaTool = new SchemaTool($entityManager);
        $this->classes = $entityManager->getMetadataFactory()->getAllMetadata();

        return $this;
    }

    /**
     * @BeforeScenario @createSchema
     */
    public function createSchema()
    {
        $this->schemaTool->createSchema($this->classes);
    }

    /**
     * @BeforeScenario @dropSchema
     */
    public function dropSchema()
    {
        $this->schemaTool->dropSchema($this->classes);
    }

    /**
     * @Given the database is empty
     * @Then I empty the database
     */
    public function emptyDatabase()
    {
        $this->dropSchema();
        $this->createSchema();
    }
}
