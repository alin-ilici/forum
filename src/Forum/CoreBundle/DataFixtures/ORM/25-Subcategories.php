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
        $s1->setName('Windows 10');
        $s1->setCategory($categoryIds[0]);
        $s1->setDescription('Totul despre Windows 10... Da, are Start Menu :)');

        $manager->persist($s1);

        $s2 = new Subcategory();
        $s2->setName('Windows 8');
        $s2->setCategory($categoryIds[0]);
        $s2->setDescription('Totul despre Windows 8');

        $manager->persist($s2);

        $s3 = new Subcategory();
        $s3->setName('Știri / Open Source');
        $s3->setCategory($categoryIds[1]);
        $s3->setDescription('Știri open-source, discuții despre ceea ce oferă sau ar trebui să ofere programele cu codul sursă public (open-source) și comunitățile din jurul lor, inițiative open-source românești');

        $manager->persist($s3);

        $s4 = new Subcategory();
        $s4->setName('Depanare sistem');
        $s4->setCategory($categoryIds[2]);
        $s4->setDescription('Aveti o problema cu sistemul vostru? Poate gasiti rezolvarea aici daca povestiti cu grija ce nu merge...');

        $manager->persist($s4);

        $s5 = new Subcategory();
        $s5->setName('Periferice');
        $s5->setCategory($categoryIds[2]);
        $s5->setDescription('Tastaturi, mouse, boxe, casti, imprimante, camere web, etc.');

        $manager->persist($s5);

        $s6 = new Subcategory();
        $s6->setName('Laptop');
        $s6->setCategory($categoryIds[2]);
        $s6->setDescription('Ai un laptop? Vrei un laptop? Aici e locul cel mai bun sa te informezi in legatura cu noile aparitii, problemele inerente etc.');

        $manager->persist($s6);

        $s7 = new Subcategory();
        $s7->setName('Android');
        $s7->setCategory($categoryIds[3]);
        $s7->setDescription('Platforma mobila nr. 1.');

        $manager->persist($s7);

        $s8 = new Subcategory();
        $s8->setName('iOS (iPhone/iPod/iPad');
        $s8->setCategory($categoryIds[3]);
        $s8->setDescription('Despre iPod, iPhone si iPad.');

        $manager->persist($s8);

        $s9 = new Subcategory();
        $s9->setName('Auto news');
        $s9->setCategory($categoryIds[4]);
        $s9->setDescription('Concept-caruri, spyshoturi, tendinte si noutati tehnice si economice din lumea automobilistica...');

        $manager->persist($s9);

        $s10 = new Subcategory();
        $s10->setName('Vacante in strainatate');
        $s10->setCategory($categoryIds[5]);
        $s10->setDescription('Pentru cei ce vor sa vada si alte locuri... Aici puteti gasi informatii pretioase adunate de la cei ce si-au luat deja teapa sau au fost foarte inspirati :) E bine sa te informezi inainte sa pleci undeva!');

        $manager->persist($s10);

        $s11 = new Subcategory();
        $s11->setName('Vacante in Romania');
        $s11->setCategory($categoryIds[5]);
        $s11->setDescription('Desi nu la calitatea dorita, Romania are un potential turistic urias. Pana il descopera altii, haideti sa vedem impreuna ce merita sau nu vazut...');

        $manager->persist($s11);

        $s12 = new Subcategory();
        $s12->setName('Turism auto');
        $s12->setCategory($categoryIds[5]);
        $s12->setDescription('In tara sau afara... ne urcam in masini si mergem de ne sar capacele. Ca asa suntem noi ;) Avem masina si o folosim. In plus ne e frica de avion...');

        $manager->persist($s12);

        $manager->flush();
    }

    public function getAllCategoryIds(EntityManager $em) {
        $categoryRepository = $em->getRepository('CoreBundle:Category');
        return $categoryRecords = $categoryRepository->findAll();
    }
}