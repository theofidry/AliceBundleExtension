<?php

/*
 * This file is part of the Fidry\AliceBundleExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceBundleExtension\Context;

use Nelmio\Alice\PersisterInterface;

/**
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
interface AliceContextInterface
{
    /**
     * @BeforeScenario @createSchema
     */
    public function createSchema();

    /**
     * @BeforeScenario @dropSchema
     */
    public function dropSchema();

    /**
     * @Given the database is empty
     * @Then I empty the database
     */
    public function emptyDatabase();

    /**
     * @Given the fixtures :fixturesFiles are loaded
     * @Given the fixtures file :fixturesFiles is loaded
     * @Given the fixtures files :fixturesFiles are loaded
     * @Given the fixtures :fixturesFiles are loaded with the persister :persister
     * @Given the fixtures file :fixturesFiles is loaded with the persister :persister
     * @Given the fixtures files :fixturesFiles are loaded with the persister :persister
     *
     * @param string             $fixturesFiles Path to the fixtures
     * @param PersisterInterface $persister
     */
    public function thereAreFixtures($fixturesFiles, $persister = null);

    /**
     * @param string $basePath
     *
     * @return $this
     */
    public function setBasePath($basePath);

    /**
     * @return string
     */
    public function getBasePath();
}
