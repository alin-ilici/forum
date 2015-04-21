<?php

namespace Forum\CoreBundle\DataFixtures\ORM;

use Doctrine\ORM\EntityManager;
use Forum\CoreBundle\Entity\Message;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Fixtures for the Topic Entity
 */
class Messages extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 35;
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
        $topicIds = $this->getAllTopicIds($manager);
        $userIds = $this->getAllUserIds($manager);

        $m1 = new Message();
        $m1->setName('Aenean hendrerit non nibh sodales viverra. Nam sem tellus, dictum sed dui nec, faucibus mattis.');
        $m1->setTopic($topicIds[0]);
        $m1->setUser($userIds[0]);

        $manager->persist($m1);

        $m2 = new Message();
        $m2->setName('Vestibulum quis sodales nulla. Nunc a massa neque. Etiam elit elit, condimentum vitae maximus et, consectetur a nunc. Vestibulum a ornare odio. Nulla sit amet.');
        $m2->setTopic($topicIds[0]);
        $m2->setUser($userIds[1]);

        $manager->persist($m2);

        $m3 = new Message();
        $m3->setName('Mauris venenatis ipsum quam, id dictum lacus auctor eu. Quisque pellentesque sed erat eu malesuada. Nunc sodales lacus nec elit.');
        $m3->setTopic($topicIds[0]);
        $m3->setUser($userIds[2]);

        $manager->persist($m3);

        $m4 = new Message();
        $m4->setName('Quisque rutrum cursus semper. Fusce accumsan consectetur porta. Suspendisse potenti. Vestibulum vel nulla felis. Donec ultricies eu metus id suscipit.');
        $m4->setTopic($topicIds[1]);
        $m4->setUser($userIds[1]);

        $manager->persist($m4);

        $m5 = new Message();
        $m5->setName('Nunc malesuada sodales tincidunt. Nullam auctor semper justo, rhoncus laoreet.');
        $m5->setTopic($topicIds[1]);
        $m5->setUser($userIds[2]);

        $manager->persist($m5);

        $m6 = new Message();
        $m6->setName('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut malesuada, lectus nec finibus blandit, tortor.');
        $m6->setTopic($topicIds[1]);
        $m6->setUser($userIds[0]);

        $manager->persist($m6);

        $m7 = new Message();
        $m7->setName('Fusce venenatis pulvinar imperdiet. Integer mattis vel risus non imperdiet. Sed interdum fermentum bibendum. Etiam vestibulum, dui sit amet condimentum mattis, neque tellus feugiat magna, at commodo ex lacus ut.');
        $m7->setTopic($topicIds[2]);
        $m7->setUser($userIds[2]);

        $manager->persist($m7);

        $m8 = new Message();
        $m8->setName('Morbi pulvinar leo nisl, quis volutpat velit aliquet eget. Nulla maximus enim vitae nulla sagittis.');
        $m8->setTopic($topicIds[2]);
        $m8->setUser($userIds[0]);

        $manager->persist($m8);

        $m9 = new Message();
        $m9->setName('Curabitur venenatis ante orci. Phasellus condimentum tristique orci ac interdum. Integer mollis malesuada purus non porta. Donec blandit aliquet orci.');
        $m9->setTopic($topicIds[2]);
        $m9->setUser($userIds[1]);

        $manager->persist($m9);

        $manager->flush();
    }

    public function getAllTopicIds(EntityManager $em) {
        $topicRepository = $em->getRepository('CoreBundle:Topic');
        return $topicRecords = $topicRepository->findAll();
    }

    public function getAllUserIds(EntityManager $em) {
        $userRepository = $em->getRepository('CoreBundle:User');
        return $userRecords = $userRepository->findAll();
    }
}