<?php

/*
 * Created by Samuel Moncarey
 * 14/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Twig\Helper;

use MoncaretWS\ContentWidgetsBundle\Entity\Container\MasterContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\LayoutWidget;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer;
use MoncaretWS\ContentWidgetsBundle\Manager\WidgetContainerManager;
use MoncaretWS\ContentWidgetsBundle\Manager\WidgetManager;


/**
 * Class WidgetRenderer
 * @package MoncaretWS\ContentWidgetsBundle\Service
 */
class WidgetRenderer {

    /**
     * @var WidgetContainerManager
     */
    private $containerManager;

    /**
     * @var WidgetManager
     */
    private $widgetManager;

    /**
     * WidgetRenderer constructor.
     *
     * @param WidgetContainerManager $containerManager
     * @param WidgetManager $widgetManager
     */
    public function __construct(WidgetManager $widgetManager, WidgetContainerManager $containerManager) {
        $this->containerManager = $containerManager;
        $this->widgetManager = $widgetManager;
    }

    /**
     * Render a widget container
     *
     * @param WidgetContainer|string $widgetContainer
     * @param array $context
     * @param \Twig_Environment $twig
     *
     * @return string
     */
    public function renderWidgetContainer($widgetContainer, $context, \Twig_Environment $twig) {

        if (!$widgetContainer instanceof WidgetContainer) {
            $widgetContainer = $this->containerManager->getContainerByName($widgetContainer, $context['config']['edit']);
            if ($widgetContainer === null) return '';
        }

        if ($widgetContainer instanceof MasterContainer && !$context['config']['edit']) {
            $widgetContainer = $this->containerManager->getPublishedContainer($widgetContainer);
            if ($widgetContainer === false) return '';
        }

        $widgetContainerData = [
            'config' => [
                'edit' => $context['config']['edit'],
                'type' => $this->containerManager->getContainerType($widgetContainer)
            ],
            'container' => $widgetContainer
        ];

        return $twig->render('@SamuelmcFdnContentWidgets/widget_templates/widget_container.html.twig', $widgetContainerData);
    }

    /**
     * Render a widget
     *
     * @param Widget $widget
     * @param array $context
     * @param \Twig_Environment $twig
     *
     * @return string
     */
    public function renderWidget(Widget $widget, $context, \Twig_Environment $twig) {

        $widgetData = [
            'config' => [
                'edit' => $context['config']['edit'],
                'type' => $this->widgetManager->getWidgetType($widget),
                'isLayout' => (bool) ($widget instanceof LayoutWidget)
            ],
            'widget' => $widget
        ];

        return $twig->render($widget->getTemplate(), $widgetData);
    }

}