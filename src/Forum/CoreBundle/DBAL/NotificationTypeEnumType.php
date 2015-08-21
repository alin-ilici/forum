<?php
namespace Forum\CoreBundle\DBAL;

class NotificationTypeEnumType extends DoctrineEnumType
{
    protected $name = 'notificationTypeEnumType';
    protected $values = array('newPrivateMessage', 'privateMessageResponse', 'like', 'friendRequest');
}