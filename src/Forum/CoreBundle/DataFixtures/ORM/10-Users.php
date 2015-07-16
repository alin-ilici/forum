<?php

namespace Forum\CoreBundle\DataFixtures\ORM;

use Forum\CoreBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixtures for the User Entity
 */
class Users extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 10;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $u1 = new User();
        $u1->setUsername('alin_ilici');
        $u1->setPassword('alinilici');
        $u1->setFirstName('Alin');
        $u1->setLastName('Ilici');
        $u1->setEmail('nicolae-alin.ilici@my.fmi.unibuc.ro');
        $u1->setRoles('ROLE_ADMINISTRATOR');

        $manager->persist($u1);

        $u2 = new User();
        $u2->setUsername('bogdan_catalin');
        $u2->setPassword('bogdancatalin');
        $u2->setFirstName('Bogdan');
        $u2->setLastName('Catalin');
        $u2->setEmail('bogdan.catalin@my.fmi.unibuc.ro');
        $u2->setRoles('ROLE_USER');

        $manager->persist($u2);

        $u3 = new User();
        $u3->setUsername('cosmin_nicolae');
        $u3->setPassword('cosminnicolae');
        $u3->setFirstName('Cosmin');
        $u3->setLastName('Nicolae');
        $u3->setEmail('cosmin.nicolae@my.fmi.unibuc.ro');
        $u3->setRoles('ROLE_MODERATOR');

        $manager->persist($u3);

        $manager->flush();
    }
}