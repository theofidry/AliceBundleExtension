<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Fidry\AliceFixturesExtension\Context\AliceContext;

/**
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class AliceContextInitializer implements ContextInitializer
{
    /**
     * @var string
     */
    private $fixturesBasePath;

    /**
     * @param string $fixturesBasePath
     */
    public function __construct($fixturesBasePath)
    {
        $this->fixturesBasePath = $fixturesBasePath;
    }

    /**
     * Initializes provided context.
     *
     * @param Context $context
     */
    public function initializeContext(Context $context)
    {
        //        if (false === $context instanceof AliceContext) {
//            return;
//        }
//
//        /** @var AliceContext $context */
//        if (null === $context->getBasePath()) {
//            $context->setBasePath('aze');
//        }
    }
}
