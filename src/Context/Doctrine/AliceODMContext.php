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

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\SchemaManager;

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
    public function createSchema()
    {
        $schemaManager = $this->getSchemaManager();

        $schemaManager->createCollections();
        $schemaManager->ensureIndexes();
    }

    /**
     * {@inheritdoc}
     */
    public function dropSchema()
    {
        $schemaManager = $this->getSchemaManager();

        $schemaManager->deleteIndexes();
        $schemaManager->dropCollections();
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
        return $this->resolvePersister($this->getDocumentManager());
    }

    /**
     * {@inheritdoc}
     */
    protected function getFixturesFinder()
    {
        return $this->kernel->getContainer()->get('hautelook_alice.doctrine.mongodb.fixtures_finder');
    }

    /**
     * @return SchemaManager
     */
    private function getSchemaManager()
    {
        return $this->getDocumentManager()->getSchemaManager();
    }

    /**
     * @return DocumentManager
     */
    private function getDocumentManager()
    {
        return $this->kernel->getContainer()->get('doctrine_mongodb.odm.default_document_manager');
    }
}
