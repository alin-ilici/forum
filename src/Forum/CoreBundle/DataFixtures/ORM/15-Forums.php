<?php

namespace Forum\CoreBundle\DataFixtures\ORM;

use Forum\CoreBundle\Entity\Forum;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixtures for the Forum Entity
 */
class Forums extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 15;
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $f1 = new Forum();
        $f1->setName('Soft Related / OS');

        $manager->persist($f1);

        $f2 = new Forum();
        $f2->setName('Professional Zone');

        $manager->persist($f2);

        $f3 = new Forum();
        $f3->setName('Pasiuni Comune');

        $manager->persist($f3);

        $manager->flush();
    }
}
