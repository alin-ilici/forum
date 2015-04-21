<?php

namespace Forum\CoreBundle\Services;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Forum\CoreBundle\Entity\Category;
use Forum\CoreBundle\Entity\Forum;
use Forum\CoreBundle\Entity\Message;
use Forum\CoreBundle\Entity\Subcategory;
use Forum\CoreBundle\Entity\Topic;

/**
 * Class SluggablePostPersist
 */
class SluggablePostPersist
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        if ($entity instanceof Forum
        || $entity instanceof Category
        || $entity instanceof Subcategory
        || $entity instanceof Topic) {
            $entity->setSlug($entity->getId() . '-' . $entity->getSlug());
            $em->persist($entity);
            $em->flush();
        }

        if ($entity instanceof Message) {
            $entity->setSlug('#message' . $entity->getId());
            $em->persist($entity);
            $em->flush();
        }
    }
}