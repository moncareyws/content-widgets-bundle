<?php

/*
 * Created by Samuel Moncarey
 * 16/06/2017
 */

namespace MoncareyWS\ContentWidgetsBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer;
use MoncareyWS\ContentWidgetsBundle\Entity\Widget\ContentWidget;
use MoncareyWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget;
use MoncareyWS\ContentWidgetsBundle\Entity\Widget\Widget;


/**
 * Class WidgetManager
 * @package MoncareyWS\ContentWidgetsBundle\Service
 */
class WidgetManager {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ClassMetadata
     */
    private $widgetMetadata;

    /**
     * @var ClassMetadata
     */
    private $layoutWidgetMetadata;

    /**
     * @var ClassMetadata
     */
    private $contentWidgetMetadata;

    /**
     * WidgetManager constructor.
     *
     * @param Registry $registry
     */
    public function __construct(Registry $registry) {
        $this->em = $registry->getManager();
        $this->widgetMetadata = $this->em->getClassMetadata(Widget::class);
        $this->layoutWidgetMetadata = $this->em->getClassMetadata(LayoutWidget::class);
        $this->contentWidgetMetadata = $this->em->getClassMetadata(ContentWidget::class);
    }

    /**
     * Get all the widget types by widget type category
     *
     * @return array
     */
    public function getWidgetTypesHierachy() {
        $types = [];
        foreach ($this->widgetMetadata->discriminatorMap as $discriminator => $className) {
            /** @var ClassMetadata $typeMetadata */
            $typeMetadata = $this->em->getClassMetadata($className);
            $parentClasses = array_filter($typeMetadata->parentClasses, function($class) {return (bool) ($class!==Widget::class);});
            if ($parent = reset($parentClasses)) {
                /** @var ClassMetadata $parentMetadata */
                $parentMetadata = $this->em->getClassMetadata($parent);
                $this->setTypeData($types, $parentMetadata->discriminatorValue, true);
                $this->setTypeData($types[$parentMetadata->discriminatorValue]['types'], $discriminator);
            }
            else {
                $this->setTypeData($types, $discriminator, true);
            }
        }
        return $types;
    }

    /**
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetType(Widget $widget) {
        /** @var ClassMetadata $widgetMetadata */
        $widgetMetadata = $this->em->getClassMetadata(get_class($widget));
        return $widgetMetadata->discriminatorValue;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getWidgetTypeClass($type) {
        return $this->widgetMetadata->discriminatorMap[$type];
    }

    /**
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetParentClass(Widget $widget) {
        $typeMetadata = $this->em->getClassMetadata(get_class($widget));
        $parentClasses = array_filter($typeMetadata->parentClasses, function($class) {return (bool) ($class!==Widget::class);});
        return reset($parentClasses);
    }

    /**
     * @param string $type
     *
     * @return bool|string
     */
    public function getTypeParent($type) {
        $typeMetadata = $this->em->getClassMetadata($this->getWidgetTypeClass($type));
        $parentClasses = array_filter($typeMetadata->parentClasses, function($class) {return (bool) ($class!==Widget::class);});
        if ($parent = reset($parentClasses)) {
            return $this->em->getClassMetadata($parent)->discriminatorValue;
        }
        return false;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function isLayoutType($type) {
        return (bool) ($this->getWidgetTypeClass($this->getTypeParent($type)) == LayoutWidget::class);
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isContentType($type) {
        return (bool) ($this->getWidgetTypeClass($this->getTypeParent($type)) == ContentWidget::class);
    }

    public function deleteWidget(Widget $widget)
    {
        if ($widget instanceof LayoutWidget) {
            /** @var ChildContainer[] $children */
            $children = $widget->getChildren();

            foreach ($children as $child) {
                $widgets = $child->getWidgets();

                foreach ($widgets as $subWidget) {
                    $this->deleteWidget($subWidget);
                }

                $this->em->remove($child);
                $this->em->flush();
            }
        }

        $this->em->remove($widget);
        $this->em->flush();
    }

    /**
     * @param array $types
     * @param string $discriminator
     * @param bool $hasSubTypes
     */
    private function setTypeData(&$types, $discriminator, $hasSubTypes = false) {
        if (!isset($types[$discriminator])) {
            $types[$discriminator] = [
                'name' => $discriminator,
                'title' => Inflector::classify($discriminator),
            ];
        }
        if(!isset($types[$discriminator]['types']) && $hasSubTypes) {
            $types[$discriminator]['types'] = [];
        }
    }

}