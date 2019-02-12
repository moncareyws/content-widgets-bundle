<?php

/*
 * Created by Samuel Moncarey
 * 15/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Factory;


use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\MasterContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget;

class WidgetContainerFactory {

    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;


    public function __construct(Doctrine $doctrine) {

        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->classMetadata = $this->em->getClassMetadata(WidgetContainer::class);
    }

    public function createStandaloneContainer($name) {

        $container = new MasterContainer(Inflector::tableize($name), true);
        return $this->save($container);
    }

    public function createMasterContainer($name) {

        $container = new MasterContainer(Inflector::tableize($name));
        return $this->save($container);
    }

    public function createChildContainer(LayoutWidget $widget, $columnName) {

        $container = new ChildContainer($this->generateChildContainerName($widget, $columnName));

        $container->setLabel(Inflector::classify($columnName));
        $container->setWidget($widget);

        return $this->save($container);
    }

    private function generateChildContainerName(LayoutWidget $widget, $columnName) {

        $widgetMetadata = $this->em->getClassMetadata(get_class($widget));
        $widgetType = $widgetMetadata->getTableName();
        $widgetId = $widget->getId();

        return Inflector::tableize("{$widgetType}_{$widgetId}_{$columnName}");
    }

    private function save(WidgetContainer $container) {

        $this->em->persist($container);
        $this->em->flush($container);

        return $container;
    }

}