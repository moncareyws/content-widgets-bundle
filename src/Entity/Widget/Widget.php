<?php

namespace MoncareyWS\ContentWidgetsBundle\Entity\Widget;


use Doctrine\ORM\Mapping as ORM;
use MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer;

/**
 * Widget
 *
 * @ORM\Entity(repositoryClass="MoncareyWS\ContentWidgetsBundle\Repository\WidgetRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class Widget implements \Serializable
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var WidgetContainer
     *
     * @ORM\ManyToOne(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer", inversedBy="widgets")
     * @ORM\JoinColumn(name="container_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $container;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string")
     */
    protected $template;

    /**
     * @var int
     *
     * @ORM\Column(name="position", type="integer")
     */
    protected $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="hidden", type="boolean")
     */
    protected $hidden = false;

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
     * Set template
     *
     * @param string $template
     *
     * @return Widget
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Widget
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set hidden
     *
     * @param boolean $hidden
     *
     * @return Widget
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set container
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container
     *
     * @return Widget
     */
    public function setContainer(\MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container
     *
     * @return \MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Get parent
     *
     * @return WidgetContainer
     */
    public function getParent() {
        return $this->getContainer();
    }
}
