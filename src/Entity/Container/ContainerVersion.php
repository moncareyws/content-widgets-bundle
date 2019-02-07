<?php

namespace MoncaretWS\ContentWidgetsBundle\Entity\Container;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContainerVersion
 *
 * @ORM\Table(name="container_version")
 * @ORM\Entity(repositoryClass="MoncaretWS\ContentWidgetsBundle\Repository\ContainerVersionRepository")
 */
class ContainerVersion
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var MasterContainer
     *
     * @ORM\ManyToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\MasterContainer", inversedBy="vesions")
     * @ORM\JoinColumn(name="container_id", referencedColumnName="id")
     */
    private $container;

    /**
     * @var int
     *
     * @ORM\Column(name="version_index", type="integer")
     */
    private $versionIndex;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var resource
     *
     * @ORM\Column(name="version_data", type="blob")
     */
    private $versionData;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_published_version", type="boolean")
     */
    private $isPublishedVersion;


    public function __construct(MasterContainer $container) {
        $this->container = $container;
        $this->versionIndex = $container->getNextVersionIndex();
        $this->createdAt = new \DateTime();
        $this->versionData = $container->serialize();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set containerId
     *
     * @param MasterContainer $container
     *
     * @return ContainerVersion
     */
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get containerId
     *
     * @return MasterContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set versionIndex
     *
     * @param integer $versionIndex
     *
     * @return ContainerVersion
     */
    public function setVersionIndex($versionIndex)
    {
        $this->versionIndex = $versionIndex;

        return $this;
    }

    /**
     * Get versionIndex
     *
     * @return int
     */
    public function getVersionIndex()
    {
        return $this->versionIndex;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContainerVersion
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set versionData
     *
     * @param string $versionData
     *
     * @return ContainerVersion
     */
    public function setVersionData($versionData)
    {
        $this->versionData = $versionData;

        return $this;
    }

    /**
     * Get versionData
     *
     * @return resource
     */
    public function getVersionData()
    {
        return $this->versionData;
    }

    /**
     * Set isPublishedVersion
     *
     * @param boolean $isPublishedVersion
     *
     * @return ContainerVersion
     */
    public function setIsPublishedVersion($isPublishedVersion)
    {
        $this->isPublishedVersion = $isPublishedVersion;

        return $this;
    }

    /**
     * Get isPublishedVersion
     *
     * @return boolean
     */
    public function getIsPublishedVersion()
    {
        return $this->isPublishedVersion;
    }

    /**
     * Get isPublishedVersion
     *
     * @return boolean
     */
    public function isPublishedVersion()
    {
        return $this->isPublishedVersion;
    }
}
