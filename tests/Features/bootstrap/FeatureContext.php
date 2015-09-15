<?php

/*
 * This file is part of the Fidry\AliceFixturesExtension package.
 *
 * (c) Théo FIDRY <theo.fidry@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Behat\Symfony2Extension\Context\KernelAwareContext;
use Doctrine\ORM\EntityManager;
use Fidry\AliceFixturesExtension\Tests\Functional\Bundle\TestBundle\Entity\AnotherDummy;
use Fidry\AliceFixturesExtension\Tests\Functional\Bundle\TestBundle\Entity\Dummy;
use PHPUnit_Framework_Assert as PHPUnit;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class FeatureContext.
 *
 * @author Théo FIDRY <theo.fidry@gmail.com>
 */
class FeatureContext implements KernelAwareContext
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * Sets Kernel instance.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->entityManager = $kernel->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @Then /^the database should be empty$/
     */
    public function theDatabaseShouldBeEmpty()
    {
        $entities = array_merge(
            $this->entityManager->getRepository('TestBundle:Dummy')->findAll(),
            $this->entityManager->getRepository('TestBundle:AnotherDummy')->findAll()
        );

        PHPUnit::assertCount(0, $entities);
    }

    /**
     * @Given /^there is (\d+) "([^"]*)" entities$/
     */
    public function thereIsEntities($nbr, $class)
    {
        for ($i = 0; $i < $nbr; $i++) {
            switch ($class) {
                case 'dummy':
                    $entity = new Dummy();
                    $entity->name = sprintf('Dummy %d', $i);
                    break;

                case 'another_dummy':
                    $entity = new AnotherDummy();
                    $entity->name = sprintf('Dummy %d', $i);
                    break;

                default:
                    throw new \UnexpectedValueException(sprintf('Unknown %s entity', $class));
            }

            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
        $this->entityManager->clear('Fidry\AliceFixturesExtension\Tests\Functional\Bundle\TestBundle\Entity\Dummy');
        $this->entityManager->clear(
            'Fidry\AliceFixturesExtension\Tests\Functional\Bundle\TestBundle\Entity\AnotherDummy'
        );
    }

    /**
     * @Then /^the database should contain (\d+) "([^"]*)" entities$/
     */
    public function thereShouldBeEntities($nbr, $class)
    {
        switch ($class) {
            case 'dummy':
                $repository = $this->entityManager->getRepository('TestBundle:Dummy');
                break;

            case 'another_dummy':
                $repository = $this->entityManager->getRepository('TestBundle:AnotherDummy');
                break;

            default:
                throw new \UnexpectedValueException(sprintf('Unknown %s entity', $class));
        }

        PHPUnit::assertCount((int) $nbr, $repository->findAll());
    }
}
