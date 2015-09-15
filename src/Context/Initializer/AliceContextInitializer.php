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
use Fidry\AliceFixturesExtension\Context\AliceContextInterface;
use Fidry\AliceFixturesExtension\Context\Doctrine\AliceODMContext;
use Fidry\AliceFixturesExtension\Context\Doctrine\AliceORMContext;
use Fidry\AliceFixturesExtension\Context\Doctrine\AlicePHPCRContext;

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
        if (false === $context instanceof AliceContextInterface) {
            return;
        }

        /* @var AliceContextInterface $context */
        $fixturesBasePath = $this->fixturesBasePath;
        switch (true) {

            case $context instanceof AliceODMContext:
                $fixturesBasePath .= '/ODM';
                break;

            case $context instanceof AliceORMContext:
                $fixturesBasePath .= '/ORM';
                break;

            case $context instanceof AlicePHPCRContext:
                $fixturesBasePath .= '/PHPCR';
                break;
        }

        if (null === $context->getBasePath()) {
            $context->setBasePath($fixturesBasePath);
        }
    }
}
