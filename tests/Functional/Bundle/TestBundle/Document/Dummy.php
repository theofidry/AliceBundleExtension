<?php

/*
 * This file is part of the Fidry\AliceBundleExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceBundleExtension\Tests\Functional\Bundle\TestBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Dummy
{
    /**
     * @var int
     *
     * @MongoDB\Id()
     */
    public $id;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string")
     */
    public $name;
}
