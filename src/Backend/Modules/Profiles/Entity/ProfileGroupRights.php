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
 * This is the ProfileGroupRights Entity
 *
 * @author Mathias Dewelde <mathias@dewelde.be>
 *
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="ProfileGroupRights")
 */
class ProfileGroupRights
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
     * @ORM\ManyToOne(targetEntity="ProfileGroup")
     * @ORM\JoinColumn(name="groupId", referencedColumnName="id")
     */
    private $group;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $startsOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $expiresOn;


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
     * Set startsOn
     *
     * @param \DateTime $startsOn
     * @return ProfileGroupRights
     */
    public function setStartsOn($startsOn)
    {
        $this->startsOn = $startsOn;

        return $this;
    }

    /**
     * Get startsOn
     *
     * @return \DateTime 
     */
    public function getStartsOn()
    {
        return $this->startsOn;
    }

    /**
     * Set expiresOn
     *
     * @param \DateTime $expiresOn
     * @return ProfileGroupRights
     */
    public function setExpiresOn($expiresOn)
    {
        $this->expiresOn = $expiresOn;

        return $this;
    }

    /**
     * Get expiresOn
     *
     * @return \DateTime 
     */
    public function getExpiresOn()
    {
        return $this->expiresOn;
    }

    /**
     * Set profile
     *
     * @param \Backend\Modules\Profiles\Entity\Profile $profile
     * @return ProfileGroupRights
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

    /**
     * Set group
     *
     * @param \Backend\Modules\Profiles\Entity\ProfileGroup $group
     * @return ProfileGroupRights
     */
    public function setGroup(ProfileGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \Backend\Modules\Profiles\Entity\ProfileGroup 
     */
    public function getGroup()
    {
        return $this->group;
    }
}
