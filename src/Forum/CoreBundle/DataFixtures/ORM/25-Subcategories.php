<?php

namespace Forum\CoreBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Forum\CoreBundle\Entity\Subcategory;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixtures for the Subcategory Entity
 */
class Subcategories extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 25;
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
        $categoryIds = $this->getAllCategoryIds($manager);

        $s1 = new Subcategory();
        $s1->setName('Class');
        $s1->setCategory($categoryIds[0]);
        $s1->setDescription('Class aptent taciti sociosqu ad.');

        $manager->persist($s1);

        $s2 = new Subcategory();
        $s2->setName('Duis');
        $s2->setCategory($categoryIds[0]);
        $s2->setDescription('Duis vitae purus lacus. Curabitur luctus nisl.');

        $manager->persist($s2);

        $s3 = new Subcategory();
        $s3->setName('Suspendisse');
        $s3->setCategory($categoryIds[1]);
        $s3->setDescription('Suspendisse ullamcorper tellus ac felis.');

        $manager->persist($s3);

        $s4 = new Subcategory();
        $s4->setName('Aenean');
        $s4->setCategory($categoryIds[2]);
        $s4->setDescription('Aenean sed luctus augue. Curabitur quis viverra augue. Proin in.');

        $manager->persist($s4);

        $s5 = new Subcategory();
        $s5->setName('Cras');
        $s5->setCategory($categoryIds[2]);
        $s5->setDescription('Cras vel eleifend turpis. Integer.');

        $manager->persist($s5);

        $s6 = new Subcategory();
        $s6->setName('Vestibulum');
        $s6->setCategory($categoryIds[2]);
        $s6->setDescription('Vestibulum at rhoncus ante, at.');

        $manager->persist($s6);

        $s7 = new Subcategory();
        $s7->setName('In');
        $s7->setCategory($categoryIds[3]);
        $s7->setDescription('In pretium luctus tellus, in.');

        $manager->persist($s7);

        $s8 = new Subcategory();
        $s8->setName('Scelerisque');
        $s8->setCategory($categoryIds[3]);
        $s8->setDescription('Nulla eget turpis scelerisque, molestie dui.');

        $manager->persist($s8);

        $s9 = new Subcategory();
        $s9->setName('Tristique');
        $s9->setCategory($categoryIds[4]);
        $s9->setDescription('Nam tristique dignissim lacus, eu feugiat tellus ullamcorper.');

        $manager->persist($s9);

        $s10 = new Subcategory();
        $s10->setName('Cum');
        $s10->setCategory($categoryIds[5]);
        $s10->setDescription('Cum sociis natoque penatibus et.');

        $manager->persist($s10);

        $s11 = new Subcategory();
        $s11->setName('Finibus');
        $s11->setCategory($categoryIds[5]);
        $s11->setDescription('Nam pretium quis eros non finibus. Suspendisse nec velit.');

        $manager->persist($s11);

        $s12 = new Subcategory();
        $s12->setName('Mauris');
        $s12->setCategory($categoryIds[5]);
        $s12->setDescription('Mauris vulputate molestie risus, lobortis.');

        $manager->persist($s12);

        $manager->flush();
    }

    public function getAllCategoryIds(EntityManager $em) {
        $categoryRepository = $em->getRepository('CoreBundle:Category');
        return $categoryRecords = $categoryRepository->findAll();
    }
}