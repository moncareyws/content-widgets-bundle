<?php

namespace MoncaretWS\ContentWidgetsBundle\Controller;

use MoncaretWS\ContentWidgetsBundle\Entity\Container\ContainerVersion;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\MasterContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\ContentWidget;
use MoncaretWS\ContentWidgetsBundle\HttpFoundation\WidgetPluginResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MoncaretWS\ContentWidgetsBundle\Entity\Widget\Widget;


/**
 * Content widgets controller.
 */
class ContentWidgetsController extends AbstractController {

    /**
     * Lists all Widget types.
     */
    public function getWidgetTypes(): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();
        $types    = $this->get('widget_manager')->getWidgetTypesHierachy();

        return $response
                ->setAction('open-reveal')
                ->setHtml($this->renderView('@content_widgets/widgets_admin/widget_types.html.twig', ['types' => $types]));
    }

    /**
     * Creates a new Widget.
     *
     * @param Request $request
     * @param WidgetContainer $container
     * @param $type
     *
     * @return WidgetPluginResponse
     */
    public function createWidget(Request $request, WidgetContainer $container, $type): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();
        $widgetCreation = $this->get('widget_factory')->creteWidgetByType($type, $container, $request);

        if ($widgetCreation['status'] === 'done') {
            $response
                ->setAction('display-new-widget')
                ->setHtml($this->get('widget_renderer')->renderWidget($widgetCreation['widget'], ['config' => ['edit' => true]], $this->get('twig')));
        }

        if ($widgetCreation['status'] === 'form_required') {
            /** @var Form $widgetCreation['form'] */
            $html = $this->renderView('@content_widgets/widgets_admin/create_widget.html.twig', [
                'widget' => $widgetCreation['widget'],
                'form'   => $widgetCreation['form']->createView(),
            ]);

            $response
                ->setAction('open-reveal')
                ->setHtml($html);
        }

        return $response;
    }

    /**
     * Displays a form to edit an existing Widget.
     *
     * @param Request $request
     * @param Widget $widget
     *
     * @return WidgetPluginResponse
     */
    public function editWidget(Request $request, Widget $widget): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();

        $form = $this->createForm($widget->getFormTypeClass(), $widget, [
            'action' => $this->generateUrl('edit_widget', ['id' => $widget->getId()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($widget);
            $em->flush();

            return $response
                ->setAction('update-widget')
                ->setHtml($this->get('widget_renderer')->renderWidget($widget, ['config' => ['edit' => true]], $this->get('twig')));
        }

        return $response
            ->setAction('open-reveal')
            ->setHtml($this->renderView('@content_widgets/widgets_admin/edit_widget.html.twig', [
                'widget' => $widget,
                'form'   => $form->createView(),
            ]));
    }

    /**
     * Toggle the widget's hidden state
     *
     * @param Widget $widget
     *
     * @return WidgetPluginResponse
     */
    public function toggleWidget(Widget $widget): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();

        $widget->setHidden(!$widget->getHidden());
        $this->getDoctrine()->getManager()->persist($widget);
        $this->getDoctrine()->getManager()->flush();


        return $response
            ->setAction('update-widget')
            ->setHtml($this->get('widget_renderer')->renderWidget($widget, ['config' => ['edit' => true]], $this->get('twig')));
    }

    /**
     * Deletes a Widget.
     *
     * @param Request $request
     * @param Widget $widget
     *
     * @return WidgetPluginResponse
     */
    public function deleteWidget(Request $request, Widget $widget): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();

        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('delete_widget', ['id' => $widget->getId()]))
                     ->setMethod('DELETE')
                     ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($widget);
            $em->flush();

            return $response
                ->setAction('remove-widget')
                ->addMessage('success', 'Widget deleted.');
        }

        return $response
            ->setAction('open-reveal')
            ->setHtml($this->renderView('@content_widgets/widgets_admin/delete_widget.html.twig', [
                'widget' => $widget,
                'form'   => $form->createView(),
            ]));
    }

    public function moveWidget(Widget $widget, $containerName, $position): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();
        $container = $this->get('widget_container_manager')->getContainerByName($containerName);
        $em = $this->getDoctrine()->getManager();

        if ($widget->getContainer()->getName() != $containerName) {
            $oldContainer = $widget->getContainer();
            $index = 0;
            foreach ($oldContainer->getWidgets() as $oldContainerWidget) {
                if ($oldContainerWidget->getId() == $widget->getId()) continue;
                $oldContainerWidget->setPosition($index);
                $em->persist($oldContainerWidget);
                $index++;
            }
        }

        $index = 0;
        foreach ($container->getWidgets() as $containerWidget) {
            if ($containerWidget->getId() == $widget->getId()) continue;
            if ($index < $position) {
                $containerWidget->setPosition($index);
                $index++;
            } else {
                $index++;
                $containerWidget->setPosition($index);
            }
            $em->persist($containerWidget);
        }

        $widget->setContainer($container);
        $widget->setPosition($position);
        $em->persist($widget);
        $em->flush();

        return $response;
    }

    /**
     * Save the container as new container version.
     *
     * @param Request $request
     * @param MasterContainer $container
     *
     * @return WidgetPluginResponse
     */
    public function saveContainer(Request $request, MasterContainer $container): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();

        $version = new ContainerVersion($container);
        $form = $this->createForm('MoncaretWS\ContentWidgetsBundle\Form\Container\ContainerVersionType', $version, [
            'action' => $this->generateUrl('save_container', ['name' => $container->getName()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($version);
            $em->flush();

            return $response
                ->setAction('close-reveal')
                ->addMessage('success', 'New version saved.');
        }

        return $response
            ->setAction('open-reveal')
            ->setHtml($this->renderView('@content_widgets/widgets_admin/save_container.html.twig', [
                'version' => $version,
                'form'   => $form->createView(),
            ]));
    }

    public function getContainerVesions(MasterContainer $container): WidgetPluginResponse
    {

        $response = new WidgetPluginResponse();

        return $response
            ->setAction('open-reveal')
            ->setHtml($this->renderView('@content_widgets/widgets_admin/container_versions.html.twig', [
                'versions' => $this->get('widget_container_manager')->getContainerVersions($container)
            ]));
    }
}
