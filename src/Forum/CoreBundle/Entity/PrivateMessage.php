<?php

namespace Forum\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PrivateMessage
 *
 * @ORM\Table(name="private_message")
 * @ORM\Entity(repositoryClass="Forum\CoreBundle\Repository\PrivateMessageRepository")
 */
class PrivateMessage extends Timestampable
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
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="original_file_name", type="string", length=255, nullable=true)
     */
    private $originalFileName;

    /**
     * @var Conversation
     *
     * @ORM\ManyToOne(targetEntity="Conversation", inversedBy="privateMessages")
     * @ORM\JoinColumn(name="id_conversation", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank
     */
    private $conversation;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="privateMessages")
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
     * @return PrivateMessage
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
     * @return PrivateMessage
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
     * Set file
     *
     * @param string $file
     * @return PrivateMessage
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set originalFileName
     *
     * @param string $originalFileName
     * @return PrivateMessage
     */
    public function setOriginalFileName($originalFileName)
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    /**
     * Get originalFileName
     *
     * @return string 
     */
    public function getOriginalFileName()
    {
        return $this->originalFileName;
    }

    /**
     * Set conversation
     *
     * @param \Forum\CoreBundle\Entity\Conversation $conversation
     * @return PrivateMessage
     */
    public function setConversation(\Forum\CoreBundle\Entity\Conversation $conversation)
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get conversation
     *
     * @return \Forum\CoreBundle\Entity\Conversation 
     */
    public function getConversation()
    {
        return $this->conversation;
    }

    /**
     * Set user
     *
     * @param \Forum\CoreBundle\Entity\User $user
     * @return PrivateMessage
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
