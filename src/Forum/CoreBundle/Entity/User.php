<?php

namespace Forum\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="Forum\CoreBundle\Repository\UserRepository")
 */
class User extends Timestampable implements UserInterface, \Serializable
{
    const ROLE_USER = "ROLE_USER";
    const ROLE_MODERATOR = "ROLE_MODERATOR";
    const ROLE_ADMINISTRATOR = "ROLE_ADMINISTRATOR";

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
     * @ORM\Column(name="username", type="string", length=64, unique=true)
     * @Assert\NotBlank
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64)
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50)
     * @Assert\NotBlank
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=50)
     * @Assert\NotBlank
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=128, unique=true)
     * @Assert\NotBlank
     */
    private $email;

    /**
     * @ORM\Column(name="roles", type="userRolesSetType")
     */
    private $roles;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Topic", mappedBy="user")
     */
    private $topics;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     */
    private $messages;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topics = new \Doctrine\Common\Collections\ArrayCollection();
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();

        $this->roles = self::ROLE_USER;
        $this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string 
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return User
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get role
     *
     * @return string 
     */
    public function getRoles()
    {
        return array($this->roles);
    }

    /**
     * Add topics
     *
     * @param \Forum\CoreBundle\Entity\Topic $topics
     * @return User
     */
    public function addTopic(\Forum\CoreBundle\Entity\Topic $topics)
    {
        $this->topics[] = $topics;

        return $this;
    }

    /**
     * Remove topics
     *
     * @param \Forum\CoreBundle\Entity\Topic $topics
     */
    public function removeTopic(\Forum\CoreBundle\Entity\Topic $topics)
    {
        $this->topics->removeElement($topics);
    }

    /**
     * Get topics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTopics()
    {
        return $this->topics;
    }

    /**
     * Add messages
     *
     * @param \Forum\CoreBundle\Entity\Message $messages
     * @return User
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

    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    public function getUploadRootDir() {
        return 'bundles/core/users_avatars';
    }

    public function uploadAvatar()
    {
        // the file property can be empty if the field is not required
        if (null === $this->getAvatar()) {
            return;
        }

        $now = new \DateTime();
        $newAvatarName = mt_rand(10000, 99999) . '-' . $now->format('d-m-y-h-i-s') . '.' . $this->getAvatar()->getClientOriginalExtension();;

        // move takes the target directory and then the
        // target filename to move to
        // second param is the new photo name uploaded to server
        $this->getAvatar()->move(
            $this->getUploadRootDir(),
            $newAvatarName
        );

        // set the path property to the filename where you've saved the file
        $this->avatar = $newAvatarName;
    }
}
