<?php

namespace Forum\CoreBundle\DataFixtures\ORM;

use Forum\CoreBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Fixtures for the User Entity
 */
class Users extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
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
    public function getOrder()
    {
        return 10;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $u1 = new User();
        $u1->setUsername('alin_ilici');
        $encoded = $encoder->encodePassword($u1, 'alinilici');
        $u1->setPassword($encoded);
        $u1->setFirstName('Alin');
        $u1->setLastName('Ilici');
        $u1->setEmail('nicolae-alin.ilici@my.fmi.unibuc.ro');
        $u1->setRoles('ROLE_ADMINISTRATOR');
        $u1->setAvatar('Wolf.jpeg');

        $manager->persist($u1);

        $u2 = new User();
        $u2->setUsername('bogdan_catalin');
        $encoded = $encoder->encodePassword($u2, 'bogdancatalin');
        $u2->setPassword($encoded);
        $u2->setFirstName('Bogdan');
        $u2->setLastName('Catalin');
        $u2->setEmail('bogdan.catalin@my.fmi.unibuc.ro');
        $u2->setRoles('ROLE_USER');

        $manager->persist($u2);

        $u3 = new User();
        $u3->setUsername('cosmin_nicolae');
        $encoded = $encoder->encodePassword($u3, 'cosminnicolae');
        $u3->setPassword($encoded);
        $u3->setFirstName('Cosmin');
        $u3->setLastName('Nicolae');
        $u3->setEmail('cosmin.nicolae@my.fmi.unibuc.ro');
        $u3->setRoles('ROLE_MODERATOR');

        $manager->persist($u3);

        $manager->flush();
    }
}