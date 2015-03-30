<?php

namespace Backend\Modules\Profiles\Entity;

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * This is the ProfileSession Entity
 *
 * @author Mathias Dewelde <mathias@dewelde.be>
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="ProfileSession")
 */
class ProfileSession
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", length=11)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Profile")
     * @ORM\JoinColumn(name="profileId", referencedColumnName="id")
     */
    private $profile;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     **/
    private $sessionId;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $secretKey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startedOn;


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
     * Set sessionId
     *
     * @param string $sessionId
     * @return ProfileSession
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string 
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set secretKey
     *
     * @param string $secretKey
     * @return ProfileSession
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;

        return $this;
    }

    /**
     * Get secretKey
     *
     * @return string 
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * Set startedOn
     *
     * @param \DateTime $startedOn
     * @return ProfileSession
     */
    public function setStartedOn($startedOn)
    {
        $this->startedOn = $startedOn;

        return $this;
    }

    /**
     * Get startedOn
     *
     * @return \DateTime 
     */
    public function getStartedOn()
    {
        return $this->startedOn;
    }

    /**
     * Set profile
     *
     * @param \Backend\Modules\Profiles\Entity\Profile $profile
     * @return ProfileSession
     */
    public function setProfile(Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Backend\Modules\Profiles\Entity\Profile 
     */
    public function getProfile()
    {
        return $this->profile;
    }
}
