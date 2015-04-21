<?php
namespace Forum\CoreBundle\DBAL;

class UserRoleEnumType extends DoctrineEnumType
{
    protected $name = 'userRoleEnumType';
    protected $values = array('user', 'moderator', 'administrator');
}