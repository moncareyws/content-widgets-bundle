<?php

namespace MoncaretWS\ContentWidgetsBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;
use MoncaretWS\ContentWidgetsBundle\Form\Widget\HtmlWidgetType;

/**
 * Foundation4ColumnsWidget
 *
 * @ORM\Entity(repositoryClass="MoncaretWS\ContentWidgetsBundle\Repository\WidgetRepository")
 */
class HtmlWidget extends ContentWidget
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="html", type="text")
     */
    protected $html = '';


    public function __construct() {
        $this->template = '@SamuelmcFdnContentWidgetsBundle/widget_templates/html.widget.html.twig';
        $this->formTypeClass = HtmlWidgetType::class;
    }

    /**
     * Set html
     *
     * @param string $html
     *
     * @return HtmlWidget
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Get container
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function getValues()
    {
        return ['html' => $this->html];
    }

    public function serialize()
    {
        return serialize([
            'id' => $this->id,
            'template' => $this->template,
            'position' => $this->position,
            'hidden' => $this->hidden,
            'html' => $this->html
        ]);
    }

    public function unserialize($serialized)
    {
        $versionData = unserialize($serialized);

        $this->id = $versionData['id'];
            $this->template = $versionData['template'];
            $this->position = $versionData['position'];
            $this->hidden = $versionData['hidden'];
            $this->html = $versionData['html'];
    }

}
