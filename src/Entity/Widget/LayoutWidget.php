<?php

/*
 * Created by Samuel Moncarey
 * 16/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;


/**
 * LayoutWidget
 *
 * @ORM\Entity(repositoryClass="MoncaretWS\ContentWidgetsBundle\Repository\WidgetRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class LayoutWidget extends Widget {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getChildren() {
        return $this->getChildContainers();
    }

    abstract public function getChildContainers();

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
     * @return LayoutWidget
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
     * @return LayoutWidget
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
     * @return LayoutWidget
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
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container
     *
     * @return LayoutWidget
     */
    public function setContainer(\MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer
     */
    public function getContainer()
    {
        return $this->container;
    }
}
