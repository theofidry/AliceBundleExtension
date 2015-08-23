<?php

/*
 * This file is part of the Hautelook\AliceBundle package.
 *
 * (c) Baldur Rensch <brensch@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension\Tests\SymfonyApp\TestBundle\DataFixtures\Faker\Provider;

class FooProvider
{
    public static function foo($str)
    {
        return 'foo'.$str;
    }
}
