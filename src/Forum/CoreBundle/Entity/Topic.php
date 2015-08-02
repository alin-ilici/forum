<?php

namespace Forum\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Topic
 *
 * @ORM\Table(name="topic")
 * @ORM\Entity(repositoryClass="Forum\CoreBundle\Repository\TopicRepository")
 */
class Topic extends Timestampable
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
     * @var Subcategory
     *
     * @ORM\ManyToOne(targetEntity="Subcategory", inversedBy="topics")
     * @ORM\JoinColumn(name="id_subcategory", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $subcategory;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="topics")
     * @ORM\JoinColumn(name="id_user", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="topic")
     */
    private $messages;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Topic
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
     * @return Topic
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
     * Set subcategory
     *
     * @param \Forum\CoreBundle\Entity\Subcategory $subcategory
     * @return Topic
     */
    public function setSubcategory(\Forum\CoreBundle\Entity\Subcategory $subcategory)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \Forum\CoreBundle\Entity\Subcategory 
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Set user
     *
     * @param \Forum\CoreBundle\Entity\User $user
     * @return Topic
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

    /**
     * Add messages
     *
     * @param \Forum\CoreBundle\Entity\Message $messages
     * @return Topic
     */
    public function addMessage(\Forum\CoreBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Forum\CoreBundle\Entity\Message $messages
     */
    public function removeMessage(\Forum\CoreBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
