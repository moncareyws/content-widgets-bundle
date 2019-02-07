<?php

namespace MoncaretWS\ContentWidgetsBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;
Use MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer;

/**
 * Foundation2ColumnsWidget
 *
 * @ORM\Entity(repositoryClass="MoncaretWS\ContentWidgetsBundle\Repository\WidgetRepository")
 */
class Foundation2ColumnsWidget extends LayoutWidget
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
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinColumn(name="column1_container_id", referencedColumnName="id")
     */
    protected $column1;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinColumn(name="column2_container_id", referencedColumnName="id")
     */
    protected $column2;


    public function __construct() {

        if ($this->template === null) $this->template = '@SamuelmcFdnContentWidgetsBundle/widget_templates/foundation_2_columns.widget.html.twig';
    }

    public function getChildContainers()
    {
        return [
            'column1' => $this->getColumn1(),
            'column2' => $this->getColumn2()
        ];
    }

    public function getParent() {
        $this->getContainer();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return Foundation2ColumnsWidget
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Foundation2ColumnsWidget
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set hidden
     *
     * @param boolean $hidden
     *
     * @return Foundation2ColumnsWidget
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set column1
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $column1
     *
     * @return Foundation2ColumnsWidget
     */
    public function setColumn1(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $column1 = null)
    {
        $this->column1 = $column1;

        return $this;
    }

    /**
     * Get column1
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getColumn1()
    {
        return $this->column1;
    }

    /**
     * Set column2
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $column2
     *
     * @return Foundation2ColumnsWidget
     */
    public function setColumn2(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $column2 = null)
    {
        $this->column2 = $column2;

        return $this;
    }

    /**
     * Get column2
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getColumn2()
    {
        return $this->column2;
    }

    /**
     * Set container
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container
     *
     * @return Foundation2ColumnsWidget
     */
    public function setContainer(\MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container = null)
    {
        $this->container = $container;

        return $this;
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

    public function serialize()
    {
        $serializable = [
            'id' => $this->id,
            'template' => $this->template,
            'position' => $this->position,
            'hidden' => $this->hidden,
            'column1' => $this->column1->serialize(),
            'column2' => $this->column2->serialize(),
        ];

        return serialize($serializable);
    }

    public function unserialize($serialized)
    {
        $versionData = unserialize($serialized);

        $this->id = $versionData['id'];
        $this->template = $versionData['template'];
        $this->position = $versionData['position'];
        $this->hidden = $versionData['hidden'];
        $serializedColumn1 = $versionData['column1'];
        $serializedColumn2 = $versionData['column2'];

        $column1 = new ChildContainer();
        $column1->unserialize($serializedColumn1);
        $column1->setWidget($this);
        $this->column1 = $column1;

        $column2 = new ChildContainer();
        $column2->unserialize($serializedColumn2);
        $column2->setWidget($this);
        $this->column2 = $column2;
    }
}
