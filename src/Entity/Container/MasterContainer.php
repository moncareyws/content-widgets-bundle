<?php

/*
 * Created by Samuel Moncarey
 * 16/06/2017
 */

namespace MoncareyWS\ContentWidgetsBundle\Entity\Container;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MoncareyWS\ContentWidgetsBundle\Entity\Widget\Widget;

/**
 * MasterContainer
 *
 * @ORM\Entity(repositoryClass="MoncareyWS\ContentWidgetsBundle\Repository\WidgetContainerRepository")
 */
class MasterContainer extends WidgetContainer {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_stand_alone", type="boolean")
     */
    protected $isStandAlone;

    /**
     * @var ContainerVersion[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Container\ContainerVersion", mappedBy="container")
     * @ORM\OrderBy({"versionIndex" = "DESC"})
     */
    protected $vesions;


    public function __construct($name = null, $isStandAlone = false)
    {
        parent::__construct($name);
        $this->setIsStandAlone($isStandAlone);

        $this->vesions = new ArrayCollection();
    }

    /**
     * Set isStandAlone
     *
     * @param boolean $isStandAlone
     *
     * @return MasterContainer
     */
    public function setIsStandAlone($isStandAlone)
    {
        $this->isStandAlone = $isStandAlone;

        return $this;
    }

    /**
     * Get isStandAlone
     *
     * @return boolean
     */
    public function getIsStandAlone()
    {
        return $this->isStandAlone;
    }

    /**
     * Get isStandAlone
     *
     * @return boolean
     */
    public function isStandAlone()
    {
        return $this->isStandAlone;
    }

    /**
     * Add vesion
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Container\ContainerVersion $vesion
     *
     * @return MasterContainer
     */
    public function addVesion(ContainerVersion $vesion)
    {
        $this->vesions[] = $vesion;

        return $this;
    }

    /**
     * Remove vesion
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Container\ContainerVersion $vesion
     */
    public function removeVesion(ContainerVersion $vesion)
    {
        $this->vesions->removeElement($vesion);
    }

    /**
     * Get vesions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVesions()
    {
        return $this->vesions;
    }

    /**
     * @return int
     */
    public function getNextVersionIndex() {
        return ($this->vesions->count() + 1);
    }

    /**
     * @return string
     */
    public function serialize()
    {
        $serializeable = [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
            'isStandAlone' => $this->isStandAlone,
            'widgets' => []
        ];

        /** @var Widget $widget */
        foreach ($this->widgets->toArray() as $widget) {
            $serializeable['widgets'][$widget->getId()] = [
                'className' => get_class($widget),
                'serialized' => $widget->serialize()
            ];
        }

        return serialize($serializeable);
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        $versionData = unserialize($serialized);

        $this->id = $versionData['id'];
        $this->name = $versionData['name'];
        $this->label = $versionData['label'];
        $this->isStandAlone = $versionData['isStandAlone'];
        $widgets = $versionData['widgets'];

        $this->widgets = new ArrayCollection();

        foreach ($widgets as $widgetId => $widgetData) {
            /** @var Widget $widget */
            $widget = new $widgetData['className']();
            $widget->unserialize($widgetData['serialized']);
            $widget->setContainer($this);
            $this->addWidget($widget);
        }
    }
}
