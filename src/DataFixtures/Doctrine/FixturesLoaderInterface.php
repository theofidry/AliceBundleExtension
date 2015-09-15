<?php

/*
 * This file is part of the Fidry\AliceBundleExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceBundleExtension\DataFixtures\Doctrine;

/**
 * Class responsible for loading the fixtures and persisting them into the database.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
interface FixturesLoaderInterface
{
    /**
     * Loads fixtures into the database.
     */
    public function load();

    /**
     * Empty the database and reload the fixtures into it.
     */
    public function reload();

    /**
     * Flushes the object manager.
     */
    public function flush();
}
