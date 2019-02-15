<?php

namespace MoncareyWS\ContentWidgetsBundle\Twig\Extension;

use MoncareyWS\ContentWidgetsBundle\Twig\Helper\WidgetRenderer;

class WidgetExtension extends \Twig_Extension {

    /**
     * @var WidgetRenderer
     */
    private $widgetRenderer;
    

    public function __construct(WidgetRenderer $widgetRenderer) {
        $this->widgetRenderer = $widgetRenderer;
    }

    public function getFunctions() {
        return [
            'widget_container' => new \Twig_SimpleFunction('widget_container', [$this, 'widgetContainerFunction'], [
                'needs_environment' => true,
                'needs_context' => true,
                'is_safe' => array('html')
            ]),
            'render_widget' => new \Twig_SimpleFunction('render_widget', [$this, 'renderWidgetFunction'], [
                'needs_environment' => true,
                'needs_context' => true,
                'is_safe' => array('html')
            ]),
        ];
    }

    public function widgetContainerFunction($environment, $context, $container, $edit = false) {
        if (!isset($context['config'])) $context['config'] = ['edit' => false];
        if ($edit) $context['config']['edit'] = true;
        return $this->widgetRenderer->renderWidgetContainer($container, $context, $environment);
    }

    public function renderWidgetFunction($environment, $context, $widget) {
        if (!isset($context['config'])) $context['config'] = ['edit' => false];
        return $this->widgetRenderer->renderWidget($widget, $context, $environment);
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'widget_extension';
    }
}
