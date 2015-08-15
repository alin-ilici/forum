<?php
namespace Forum\CoreBundle\DBAL;

class NotificationTypeEnumType extends DoctrineEnumType
{
    protected $name = 'notificationTypeEnumType';
    protected $values = array('privateMessage', 'like', 'friendRequest');
}