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
        $m1->setName('Buna dimineata. As dori sa stiu, daca se cunoaste până acum, de la ce varianta de Windows se va putea face upgrade , legal, la Win 10. Eu am instalat pe laptop Win 7 premium, iar pe desktop - ultimate, ambele cu licenta.');
        $m1->setTopic($topicIds[0]);
        $m1->setUser($userIds[0]);

        $manager->persist($m1);

        $m2 = new Message();
        $m2->setName('Nu-mi aduc aminte sa fi existat vreodata upgrade gratuit de la o versiune la alta, considerand 8.1 un servicepack si nu o noua versiune.');
        $m2->setTopic($topicIds[0]);
        $m2->setUser($userIds[1]);

        $manager->persist($m2);

        $m3 = new Message();
        $m3->setName('Era o poveste cu windows 8. 8.1 dar până la anu nimic nu e sigur!');
        $m3->setTopic($topicIds[0]);
        $m3->setUser($userIds[2]);

        $manager->persist($m3);

        $m4 = new Message();
        $m4->setName('Salut! Cineva a pus parola pe un laptop cu win 10 si apoi a uitat-o. Ce variante am acum? Multumesc!');
        $m4->setTopic($topicIds[1]);
        $m4->setUser($userIds[1]);

        $manager->persist($m4);

        $m5 = new Message();
        $m5->setName('<a href="https://4sysops.com/archives/reset-a-windows-10-password/">Link util</a>');
        $m5->setTopic($topicIds[1]);
        $m5->setUser($userIds[2]);

        $manager->persist($m5);

        $m6 = new Message();
        $m6->setName('Descarca administrator password remover, arde-l pe CD si boot-eaz-al! De acolo te descurci.');
        $m6->setTopic($topicIds[1]);
        $m6->setUser($userIds[0]);

        $manager->persist($m6);

        $m7 = new Message();
        $m7->setName('Am instalat si eu win 10 azi dar am observat ca nu-mi inchide PC-ul cand dau shutdown.Se innegreste ecranul, se stinge... dar hardul este bagat in hibernare. Se pare ca nu e doar la mine. Sa fie un bug? Stie cineva cum se poate rezolva? Mi se pare penibil sa inchid calculatorul din butonul de pe unitate.Merci anticipat.');
        $m7->setTopic($topicIds[2]);
        $m7->setUser($userIds[2]);

        $manager->persist($m7);

        $m8 = new Message();
        $m8->setName('Si la mine s-a intamplat; am avut aceleasi simptome, in final a trebuit sa il inchid din buton tinand apasat 4 secunde.');
        $m8->setTopic($topicIds[2]);
        $m8->setUser($userIds[0]);

        $manager->persist($m8);

        $m9 = new Message();
        $m9->setName('Da disable la hibernare. De altfel citeste si <a href="http://www.tenforums.com/tutorials/4189-fast-startup-turn-off-windows-10-a.html">aici</a> despre fast startup.');
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