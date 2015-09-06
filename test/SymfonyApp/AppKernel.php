<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension\Tests\SymfonyApp;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Fidry\AliceFixturesExtension\Tests\SymfonyApp\TestBundle\TestBundle;
use Fidry\PsyshBundle\PsyshBundle;
use Hautelook\AliceBundle\HautelookAliceBundle;
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
            new HautelookAliceBundle(),
            new TestBundle(),
            new PsyshBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config.yml');
    }
}
