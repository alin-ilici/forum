<?php
namespace Forum\CoreBundle\DBAL;

class UserRolesSetType extends DoctrineSetType
{
    protected $name = 'userRolesSetType';
    protected $values = array('ROLE_USER', 'ROLE_MODERATOR', 'ROLE_ADMINISTRATOR');
}