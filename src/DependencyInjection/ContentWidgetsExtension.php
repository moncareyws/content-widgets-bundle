<?php

namespace MoncareyWS\ContentWidgetsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;


class ContentWidgetsExtension extends Extension implements PrependExtensionInterface
{

    public function prepend(ContainerBuilder $container)
    {
        foreach ($container->getExtensionConfig('twig') as $twigConfig) {

            if (!isset($twigConfig['paths']))
                $twigConfig['paths'] = [];

            $twigConfig['paths'][__DIR__.'/../Resources/views'] = 'content_widgets';

//            if (!isset($twigConfig['form_themes']))
//                $twigConfig['form_themes'] = [];
//
//            $twigConfig['form_themes'][] = '@foundation/form/fields.html.twig';

            if (!isset($twigConfig['globals']))
                $twigConfig['globals'] = [];

            if (!isset($twigConfig['globals']['js_files']))
                $twigConfig['globals']['js_files'] = [];

            $twigConfig['globals']['js_files']+= [
                '/bundles/contentwidgets/node_modules/trumbowyg/dist/trumbowyg.js' => 10,
                '/bundles/contentwidgets/node_modules/spin/dist/spin.js' => 10,
                '/bundles/contentwidgets/node_modules/mustache/mustache.js' => 10,
                '/bundles/contentwidgets/dist/js/jquery.spin.js' => 20,
                '/bundles/contentwidgets/dist/js/foundation.ajaxImage.js' => 21,
                '/bundles/contentwidgets/dist/js/foundation.trumbowygEditor.js' => 15,
                '/bundles/contentwidgets/dist/js/foundation.widgetContainerEditor.js' => 16,
                '/bundles/contentwidgets/dist/js/foundation.widgetEditor.js' => 17,
            ];

            $container->loadFromExtension('twig', $twigConfig);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
