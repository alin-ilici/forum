<?php

namespace Forum\CoreBundle;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CoreBundle extends Bundle
{
    public function boot()
    {
        if (!Type::hasType("userRoleEnumType")) {
            /** @var EntityManager $em */
            $em = $this->container->get('doctrine.orm.entity_manager');
            Type::addType('userRoleEnumType', 'Forum\CoreBundle\DBAL\UserRoleEnumType');
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('userRoleEnumType','userRoleEnumType');
        }
    }
}
