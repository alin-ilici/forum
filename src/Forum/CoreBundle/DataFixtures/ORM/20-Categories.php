<?php

namespace Forum\CoreBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Forum\CoreBundle\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixtures for the Forum Entity
 */
class Categories extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 20;
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
        $forumIds = $this->getAllForumIds($manager);

        $c1 = new Category();
        $c1->setName('Nulla');
        $c1->setForum($forumIds[0]);

        $manager->persist($c1);

        $c2 = new Category();
        $c2->setName('Maecenas');
        $c2->setForum($forumIds[0]);

        $manager->persist($c2);

        $c3 = new Category();
        $c3->setName('Donec');
        $c3->setForum($forumIds[1]);

        $manager->persist($c3);

        $c4 = new Category();
        $c4->setName('Etiam');
        $c4->setForum($forumIds[1]);

        $manager->persist($c4);

        $c5 = new Category();
        $c5->setName('Mauris');
        $c5->setForum($forumIds[2]);

        $manager->persist($c5);

        $c6 = new Category();
        $c6->setName('Aliquam');
        $c6->setForum($forumIds[2]);

        $manager->persist($c6);

        $manager->flush();
    }

    public function getAllForumIds(EntityManager $em) {
        $forumRepository = $em->getRepository('CoreBundle:Forum');
        return $forumRecords = $forumRepository->findAll();
    }
}