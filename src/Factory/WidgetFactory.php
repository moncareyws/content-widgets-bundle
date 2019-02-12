<?php

/*
 * Created by Samuel Moncarey
 * 15/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Factory;


use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\Common\Util\Inflector;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Mapping\ClassMetadata;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\ContentWidget;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;

class WidgetFactory {

    /**
     * @var Doctrine
     */
    private $doctrine;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var WidgetManager
     */
    private $manager;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var WidgetContainerFactory
     */
    private $containerFactory;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Router
     */
    private $router;

    /**
     * WidgetFactory constructor.
     * @param Doctrine $doctrine
     * @param WidgetManager $manager
     * @param WidgetContainerFactory $containerFactory
     * @param FormFactory $formFactory
     */
    public function __construct(Doctrine $doctrine, WidgetManager $manager, WidgetContainerFactory $containerFactory, FormFactory $formFactory, Router $router) {
        $this->doctrine = $doctrine;
        $this->em = $this->doctrine->getManager();
        $this->classMetadata = $this->em->getClassMetadata(Widget::class);
        $this->manager = $manager;
        $this->containerFactory = $containerFactory;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    public function creteWidgetByType($type, WidgetContainer $container, Request $request) {
        if ($this->manager->isLayoutType($type)) {
            return $this->createLayoutWidget($type, $container);
        }

        if ($this->manager->isContentType($type)) {
            return $this->createContentWidget($type, $container, $request);
        }

        throw new \InvalidArgumentException("\"{$type}\" is not a widget type");
    }

    public function createLayoutWidget($layoutType, WidgetContainer $container) {
        $widgetClass = $this->getLayoutWidgetTypeClass($layoutType);
        /** @var LayoutWidget $widget */
        $widget = new $widgetClass();
        if (!$widget instanceof LayoutWidget) throw new \InvalidArgumentException("\"{$layoutType}\" is not a Layout widget");
        $widget->setPosition($container->countWidgets());
        $widget->setContainer($container);
        $this->save($widget);

        foreach ($this->getLayoutWidgetContainers($widget) as $childContainerData) {
            $childContainer = $this->containerFactory->createChildContainer($widget, $childContainerData['fieldName']);
            $widget->{'set' . Inflector::classify($childContainerData['fieldName'])}($childContainer);
        }

        return [
            'status' => 'done',
            'widget' => $this->save($widget)
        ];
    }

    public function createContentWidget($contentType, WidgetContainer $container, Request $request) {
        $widgetClass = $this->manager->getWidgetTypeClass($contentType);
        /** @var ContentWidget $widget */
        $widget = new $widgetClass();
        $form = $this->formFactory->create($widget->getFormTypeClass(), $widget, [
            'action' => $this->router->generate('create_widget', ['name' => $container->getName(), 'type' => $contentType])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $widget->setContainer($container);
            $widget->setPosition($container->countWidgets());
            return [
                'status' => 'done',
                'widget' => $this->save($widget)
            ];
        }

        return [
            'status' => 'form_required',
            'widget' => $widget,
            'form' => $form
        ];
    }

    public function getWidgetTypes() {
        dump($this->classMetadata->parentClasses);
        return array_keys($this->classMetadata->discriminatorMap);
    }

    public function widgetTypeExists($type) {
        return in_array($type, $this->getWidgetTypes());
    }

    public function getLayoutWidgetContainers(LayoutWidget $widget) {
        $layoutMetadata = $this->em->getClassMetadata(get_class($widget));
        return $layoutMetadata->getAssociationsByTargetClass(ChildContainer::class);
    }

    private function getLayoutWidgetTypeClass($type) {
        return $this->em->getClassMetadata(LayoutWidget::class)->discriminatorMap[$type];
    }

    private function save(Widget $widget) {

        $this->em->persist($widget);
        $this->em->flush($widget);

        return $widget;
    }

}