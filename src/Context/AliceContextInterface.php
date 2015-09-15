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
     * @Given the fixtures :fixturesFile are loaded
     * @Given the fixtures file :fixturesFile is loaded
     * @Given the fixtures :fixturesFile are loaded with the persister :persister
     * @Given the fixtures file :fixturesFile is loaded with the persister :persister
     *
     * @param string             $fixturesFile Path to the fixtures
     * @param PersisterInterface $persister
     */
    public function thereAreFixtures($fixturesFile, $persister = null);

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
