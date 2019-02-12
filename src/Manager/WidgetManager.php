<?php

/*
 * Created by Samuel Moncarey
 * 16/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\ContentWidget;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget;


/**
 * Class WidgetManager
 * @package MoncaretWS\ContentWidgetsBundle\Service
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