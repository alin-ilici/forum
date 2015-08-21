<?php

namespace Forum\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Conversation
 *
 * @ORM\Table(name="conversation")
 * @ORM\Entity(repositoryClass="Forum\CoreBundle\Repository\ConversationRepository")
 */
class Conversation extends Timestampable
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Slug(fields={"id", "name"}, unique=true)
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="conversationsFromUser")
     * @ORM\JoinColumn(name="id_from_user", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $fromUser;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="conversationsToUser")
     * @ORM\JoinColumn(name="id_to_user", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $toUser;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PrivateMessage", mappedBy="conversation")
     */
    private $privateMessages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->privateMessages = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Conversation
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Conversation
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set fromUser
     *
     * @param \Forum\CoreBundle\Entity\User $fromUser
     * @return Conversation
     */
    public function setFromUser(\Forum\CoreBundle\Entity\User $fromUser)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return \Forum\CoreBundle\Entity\User 
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param \Forum\CoreBundle\Entity\User $toUser
     * @return Conversation
     */
    public function setToUser(\Forum\CoreBundle\Entity\User $toUser)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \Forum\CoreBundle\Entity\User 
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Add privateMessages
     *
     * @param \Forum\CoreBundle\Entity\PrivateMessage $privateMessages
     * @return Conversation
     */
    public function addPrivateMessage(\Forum\CoreBundle\Entity\PrivateMessage $privateMessages)
    {
        $this->privateMessages[] = $privateMessages;

        return $this;
    }

    /**
     * Remove privateMessages
     *
     * @param \Forum\CoreBundle\Entity\PrivateMessage $privateMessages
     */
    public function removePrivateMessage(\Forum\CoreBundle\Entity\PrivateMessage $privateMessages)
    {
        $this->privateMessages->removeElement($privateMessages);
    }

    /**
     * Get privateMessages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrivateMessages()
    {
        return $this->privateMessages;
    }
}
