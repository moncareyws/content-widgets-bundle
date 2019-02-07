<?php

namespace MoncaretWS\ContentWidgetsBundle\Entity\Container;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget;

/**
 * WidgetContainer
 *
 * @ORM\Entity(repositoryClass="MoncaretWS\ContentWidgetsBundle\Repository\WidgetContainerRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class WidgetContainer implements \Serializable {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    protected $label;

    /**
     * @var Widget[]
     *
     * @ORM\OneToMany(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget", mappedBy="container", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $widgets;


    /**
     * Constructor
     */
    public function __construct($name = null) {

        if ($name !== null) {
            $this->name = strtolower($name);
            $this->label = ucfirst($name);
        }

        $this->widgets = new ArrayCollection();
    }

    public function getChildren() {
        return [];
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
     *
     * @return WidgetContainer
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
     * Set label
     *
     * @param string $label
     *
     * @return WidgetContainer
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add widget
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget $widget
     *
     * @return WidgetContainer
     */
    public function addWidget(\MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget $widget)
    {
        $this->widgets[] = $widget;

        return $this;
    }

    /**
     * Remove widget
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget $widget
     */
    public function removeWidget(\MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget $widget)
    {
        $this->widgets->removeElement($widget);
    }

    /**
     * Get widgets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWidgets()
    {
        return $this->widgets;
    }

    /**
     * Get the number of widgets inside the container
     *
     * @return int
     */
    public function countWidgets() {
        return $this->widgets->count();
    }
}
