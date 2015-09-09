<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fidry\AliceFixturesExtension\EventSubscriber;

use Behat\Behat\EventDispatcher\Event\AfterFeatureTested;
use Behat\Behat\EventDispatcher\Event\BeforeFeatureTested;
use Behat\Behat\EventDispatcher\Event\FeatureTested;
use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Behat\Behat\EventDispatcher\Event\ExampleTested;
use Fidry\AliceFixturesExtension\Extension;
use Fidry\AliceFixturesExtension\DataFixtures\Doctrine\FixturesLoaderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class HookListener implements EventSubscriberInterface
{
    /**
     * @var string
     */
    private $lifetime;

    /**
     * @var FixturesLoaderInterface
     */
    private $fixturesLoader;
    /**
     * @var FixturesLoaderInterface
     */
    private $loader;

    /**
     * @param FixturesLoaderInterface $loader
     * @param string                  $lifetime
     */
    public function __construct(FixturesLoaderInterface $loader, $lifetime)
    {
        $this->loader = $loader;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FeatureTested::BEFORE     => 'beforeFeature',
            FeatureTested::AFTER      => 'afterFeature',
            ExampleTested::BEFORE     => 'beforeScenario',
            ScenarioTested::BEFORE    => 'beforeScenario',
            ExampleTested::AFTER      => 'afterScenario',
            ScenarioTested::AFTER     => 'afterScenario',
        ];
    }

    /**
     * @param BeforeFeatureTested $event
     */
    public function beforeFeature(BeforeFeatureTested $event)
    {
        if (Extension::LIFETIME_FEATURE === $this->lifetime) {
            $this->fixturesLoader->reload();
        }
    }

    /**
     * @param AfterFeatureTested $event
     */
    public function afterFeature(AfterFeatureTested $event)
    {
        if (Extension::LIFETIME_FEATURE === $this->lifetime) {
            $this->fixturesLoader->flush();
        }
    }

    /**
     * @param ScenarioTested $event
     */
    public function beforeScenario(ScenarioTested $event)
    {
        if (Extension::LIFETIME_FEATURE === $this->lifetime) {
            $this->fixturesLoader->reload();
        }
    }

    /**
     * @param ScenarioTested $event
     */
    public function afterScenario(ScenarioTested $event)
    {
        if (Extension::LIFETIME_FEATURE === $this->lifetime) {
            $this->fixturesLoader->flush();
        }
    }
}
