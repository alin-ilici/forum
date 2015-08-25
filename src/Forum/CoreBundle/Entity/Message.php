<?php

namespace Forum\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Message
 *
 * @ORM\Table(name="message")
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
     * @var Topic
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
     * Set file
     *
     * @param string $file
     * @return Message
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
     * @return Message
     */
    public function setOriginaFileName($originalFileName)
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

    public function getUploadRootDir() {
        return 'bundles/core/messages_uploads';
    }

    public function uploadFile()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        $now = new \DateTime();
        $newFileName = mt_rand(10000, 99999) . '-' . $now->format('d-m-y-h-i-s') . '.' . $this->getFile()->getClientOriginalExtension();

        // set the original file name
        $this->originalFileName = $this->getFile()->getClientOriginalName();

        // move takes the target directory and then the target filename to move to
        // second param is the new photo name uploaded to server
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $newFileName
        );

        // set the path property to the filename where you've saved the file
        $this->file = $newFileName;
    }

    public function removeFile() {
        if (null === $this->getFile()) {
            return;
        }

        unlink($this->getUploadRootDir() . '/' . $this->file);

        $this->file = null;
        $this->originalFileName = null;
    }
}
