<?php

namespace Backend\Modules\Profiles\Entity;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This is the Profile Entity
 *
 * @author Mathias Dewelde <mathias@dewelde.be>
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="Profile")
 */
class Profile
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED = 'deleted';
    const STATUS_BLOCKED = 'blocked';

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Array
     *
     * @ORM\Column(type="array")
     **/
    private $settings;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=8)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $displayName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $registeredOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $lastLoginOn;


    /**
     * The constructor
     */
    public function __construct() {
        $this->features = new ArrayCollection();
    }

    /**
     * Set id
     *
     * @param  integer    $id
     * @return Profile
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Sets the value of status.
     *
     * @param string $status the status
     * @return Profile
     */
    public function setStatus($status)
    {
        if (!in_array($status, array(
                self::STATUS_ACTIVE,
                self::STATUS_INACTIVE,
                self::STATUS_DELETED,
                self::STATUS_BLOCKED)
        )) {
            throw new \InvalidArgumentException('Invalid profile status');
        }

        $this->status = $status;

        return $this;
    }

    /**
     * Gets the value of status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Add a setting
     *
     * @param  string    $key
     * @param  mixed     $value
     * @return Profile
     */
    public function addSetting($key, $value)
    {
        $this->settings[$key] = $value;

        return $this;
    }

    /**
     * Set settings
     *
     * @param array $settings
     * @return Profile
     */
    public function setSettings($settings)
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Remove a setting
     *
     * @param  string    $key
     * @return Profile
     */
    public function removeSetting($key)
    {
        unset($this->settings[$key]);

        return $this;
    }

    /**
     * Get setting
     *
     * @param  string    $key
     * @return mixed
     */
    public function getSetting($key)
    {
        return $this->settings[$key];
    }

    /**
     * Get settings
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Profile
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
     * Set password
     *
     * @param string $password
     * @return Profile
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
     * Set displayName
     *
     * @param string $displayName
     * @return Profile
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Profile
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set registeredOn
     *
     * @param \DateTime $registeredOn
     * @return Profile
     */
    public function setRegisteredOn($registeredOn)
    {
        $this->registeredOn = $registeredOn;

        return $this;
    }

    /**
     * Get registeredOn
     *
     * @return \DateTime
     */
    public function getRegisteredOn()
    {
        return $this->registeredOn;
    }

    /**
     * Set lastLoginOn
     *
     * @param \DateTime $lastLoginOn
     * @return Profile
     */
    public function setLastLoginOn($lastLoginOn)
    {
        $this->lastLoginOn = $lastLoginOn;

        return $this;
    }

    /**
     * Get lastLoginOn
     *
     * @return \DateTime
     */
    public function getLastLoginOn()
    {
        return $this->lastLoginOn;
    }

    /**
     *  @ORM\PrePersist
     */
    public function prePersist()
    {
        $this->registeredOn = new \DateTime();
    }
}
