<?php

namespace Forum\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Message
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Forum\CoreBundle\Repository\MessageRepository")
 */
class Message extends Timestampable
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
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(length=50, unique=true)
     */
    private $slug;

    /**
     * @var Subcategory
     *
     * @ORM\ManyToOne(targetEntity="Topic", inversedBy="messages")
     * @ORM\JoinColumn(name="id_topic", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $topic;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="messages")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $user;


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
     * @return Message
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
     * @return Message
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
     * Set topic
     *
     * @param \Forum\CoreBundle\Entity\Topic $topic
     * @return Message
     */
    public function setTopic(\Forum\CoreBundle\Entity\Topic $topic)
    {
        $this->topic = $topic;

        return $this;
    }

    /**
     * Get topic
     *
     * @return \Forum\CoreBundle\Entity\Topic 
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Set user
     *
     * @param \Forum\CoreBundle\Entity\User $user
     * @return Message
     */
    public function setUser(\Forum\CoreBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Forum\CoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
