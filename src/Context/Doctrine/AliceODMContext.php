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

use Doctrine\ODM\MongoDB\SchemaManager;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Context to load fixtures files with Alice loader.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AliceODMContext extends AbstractAliceContext
{
    /**
     * @var SchemaManager
     */
    private $schemaManager;

    /**
     * {@inheritdoc}
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->init(
            $kernel,
            $kernel->getContainer()->get('hautelook_alice.doctrine.mongodb.fixtures_finder'),
            $kernel->getContainer()->get('hautelook_alice.fixtures.loader'),
            $this->resolvePersister($kernel->getContainer()->get('doctrine_mongodb.odm.default_document_manager'))
        );

        $this->schemaManager = $kernel->getContainer()->get('doctrine_mongodb.odm.default_document_manager')->getSchemaManager();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createSchema()
    {
        $this->schemaManager->createCollections();
        $this->schemaManager->ensureIndexes();
    }

    /**
     * {@inheritdoc}
     */
    public function dropSchema()
    {
        $this->schemaManager->deleteIndexes();
        $this->schemaManager->dropCollections();
    }

    /**
     * {@inheritdoc}
     */
    public function emptyDatabase()
    {
        $this->dropSchema();
        $this->createSchema();
    }
}
