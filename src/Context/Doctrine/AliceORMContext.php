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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Context to load fixtures files with Alice loader.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AliceORMContext extends AbstractAliceContext
{
    /**
     * {@inheritdoc}
     */
    public function createSchema()
    {
        $this->getSchemaTool()->createSchema($this->getAllMetadatas());
    }

    /**
     * {@inheritdoc}
     */
    public function dropSchema()
    {
        $this->getSchemaTool()->dropSchema($this->getAllMetadatas());
    }

    /**
     * {@inheritdoc}
     */
    public function emptyDatabase()
    {
        $this->dropSchema();
        $this->createSchema();
    }

    /**
     * {@inheritdoc}
     */
    protected function getPersister()
    {
        return $this->resolvePersister($this->getEntityManager());
    }

    /**
     * {@inheritdoc}
     */
    protected function getFixturesFinder()
    {
        return $this->kernel->getContainer()->get('hautelook_alice.doctrine.orm.fixtures_finder');
    }

    /**
     * @return SchemaTool
     */
    private function getSchemaTool()
    {
        return new SchemaTool($this->getEntityManager());
    }

    /**
     * @return ClassMetadata[]
     */
    private function getAllMetadatas()
    {
        return $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
    }

    /**
     * @return EntityManagerInterface
     */
    private function getEntityManager()
    {
        return $this->kernel->getContainer()->get('doctrine.orm.entity_manager');
    }
}
