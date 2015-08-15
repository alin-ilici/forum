<?php

namespace Forum\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Topic
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="Forum\CoreBundle\Repository\NotificationRepository")
 */
class Notification extends Timestampable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="notifications")
     * @ORM\JoinColumn(name="for_id_user", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $forIdUser;

    /**
     * @var integer
     *
     * @ORM\Column(name="seen", type="integer", nullable=false)
     */
    private $seen;

    /**
     * @ORM\Column(name="type", type="notificationTypeEnumType", nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(name="extra_info", type="json_array", nullable=true)
     */
    private $extraInfo;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set seen
     *
     * @param integer $seen
     * @return Notification
     */
    public function setSeen($seen)
    {
        $this->seen = $seen;

        return $this;
    }

    /**
     * Get seen
     *
     * @return integer 
     */
    public function getSeen()
    {
        return $this->seen;
    }

    /**
     * Set type
     *
     * @param \notificationTypeEnumType $type
     * @return Notification
     */
    public function setType(\notificationTypeEnumType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \notificationTypeEnumType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set extraInfo
     *
     * @param array $extraInfo
     * @return Notification
     */
    public function setExtraInfo($extraInfo)
    {
        $this->extraInfo = $extraInfo;

        return $this;
    }

    /**
     * Get extraInfo
     *
     * @return array 
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * Set forIdUser
     *
     * @param \Forum\CoreBundle\Entity\User $forIdUser
     * @return Notification
     */
    public function setForIdUser(\Forum\CoreBundle\Entity\User $forIdUser)
    {
        $this->forIdUser = $forIdUser;

        return $this;
    }

    /**
     * Get forIdUser
     *
     * @return \Forum\CoreBundle\Entity\User 
     */
    public function getForIdUser()
    {
        return $this->forIdUser;
    }
}
