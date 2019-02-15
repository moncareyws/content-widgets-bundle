<?php

/*
 * Created by Samuel Moncarey
 * 16/06/2017
 */

namespace MoncareyWS\ContentWidgetsBundle\Entity\Container;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use MoncareyWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget;
use MoncareyWS\ContentWidgetsBundle\Entity\Widget\Widget;

/**
 * ChildContainer
 *
 * @ORM\Entity(repositoryClass="MoncareyWS\ContentWidgetsBundle\Repository\WidgetContainerRepository")
 */
class ChildContainer extends WidgetContainer {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var LayoutWidget
     *
     * @ORM\ManyToOne(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget")
     * @ORM\JoinColumn(name="widget_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $widget;

    /**
     * Set widget
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget $widget
     *
     * @return ChildContainer
     */
    public function setWidget(\MoncareyWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget $widget = null)
    {
        $this->widget = $widget;

        return $this;
    }

    /**
     * Get widget
     *
     * @return \MoncareyWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget
     */
    public function getWidget()
    {
        return $this->widget;
    }

    /**
     * Get parentWidget
     *
     * @return LayoutWidget
     */
    public function getParent()
    {
        return $this->widget;
    }

    public function serialize()
    {
        $serializeable = [
            'id' => $this->id,
            'name' => $this->name,
            'label' => $this->label,
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

    public function unserialize($serialized)
    {
        $versionData = unserialize($serialized);

        $this->id = $versionData['id'];
        $this->name = $versionData['name'];
        $this->label = $versionData['label'];
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
