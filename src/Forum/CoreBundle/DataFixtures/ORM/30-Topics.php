<?php

namespace Forum\CoreBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Forum\CoreBundle\Entity\Topic;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixtures for the Topic Entity
 */
class Topics extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 30;
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
        $subcategoryIds = $this->getAllSubcategoryIds($manager);
        $userIds = $this->getAllUserIds($manager);

        $t1 = new Topic();
        $t1->setName('Upgrade la Windows 10');
        $t1->setSubcategory($subcategoryIds[0]);
        $t1->setUser($userIds[0]);

        $manager->persist($t1);

        $t2 = new Topic();
        $t2->setName('Resetare parola Windows 10');
        $t2->setSubcategory($subcategoryIds[0]);
        $t2->setUser($userIds[1]);

        $manager->persist($t2);

        $t3 = new Topic();
        $t3->setName('Windows 10 - shutdown incomplet');
        $t3->setSubcategory($subcategoryIds[0]);
        $t3->setUser($userIds[2]);

        $manager->persist($t3);

        $manager->flush();
    }

    public function getAllSubcategoryIds(EntityManager $em) {
        $subcategoryRepository = $em->getRepository('CoreBundle:Subcategory');
        return $subcategoryRecords = $subcategoryRepository->findAll();
    }

    public function getAllUserIds(EntityManager $em) {
        $userRepository = $em->getRepository('CoreBundle:User');
        return $userRecords = $userRepository->findAll();
    }
}