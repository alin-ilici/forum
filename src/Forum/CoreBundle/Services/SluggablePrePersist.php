<?php

namespace Forum\CoreBundle\Services;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Forum\CoreBundle\Entity\Message;

/**
 * Class SluggablePrePersist
 */
class SluggablePrePersist
{
    public function generateRandomNumber() {
        $random = "";
        for ($i = 0; $i < 10; $i++) {
            $random .= mt_rand(0,9);
        }

        return $random;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof Message) {
            $entity->setSlug($this->generateRandomNumber());
        }
    }
}