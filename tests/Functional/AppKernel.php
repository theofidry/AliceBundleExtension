<?php

/*
 * This file is part of the Fidry\AliceBundleExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle;
use Fidry\AliceBundleExtension\Tests\Functional\Bundle\TestBundle\TestBundle;
use Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle;
use Fidry\PsyshBundle\PsyshBundle;
use Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new DoctrineBundle(),
            new FrameworkBundle(),
            new TestBundle(),
            new PsyshBundle(),
            new DoctrineMongoDBBundle(),
            new FidryAliceDataFixturesBundle(),
            new NelmioAliceBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }
}
