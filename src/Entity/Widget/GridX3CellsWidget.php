<?php

namespace MoncareyWS\ContentWidgetsBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;
use MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer;

/**
 * Foundation3CellsWidget
 *
 * @ORM\Entity(repositoryClass="MoncareyWS\ContentWidgetsBundle\Repository\WidgetRepository")
 */
class GridX3CellsWidget extends LayoutWidget
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", d
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinColumn(name="cell1_container_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $cell1;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinColumn(name="cell2_container_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $cell2;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinColumn(name="cell3_container_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $cell3;


    public function __construct() {
        $this->template = '@content_widgets/widget_templates/grid_x_3_cells.widget.html.twig';
    }

    public function getChildContainers()
    {
        return [
            'cell1' => $this->getCell1(),
            'cell2' => $this->getCell2(),
            'cell3' => $this->getCell3()
        ];
    }

    public function getParent() {
        return $this->getContainer();
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
     * @return GridX3CellsWidget
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
     * @return GridX3CellsWidget
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
     * @return GridX3CellsWidget
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
     * Set cell1
     *
     * @param ChildContainer $cell1
     *
     * @return GridX3CellsWidget
     */
    public function setCell1(ChildContainer $cell1 = null)
    {
        $this->cell1 = $cell1;

        return $this;
    }

    /**
     * Get cell1
     *
     * @return ChildContainer
     */
    public function getCell1()
    {
        return $this->cell1;
    }

    /**
     * Set cell2
     *
     * @param ChildContainer $cell2
     *
     * @return GridX3CellsWidget
     */
    public function setCell2(ChildContainer $cell2 = null)
    {
        $this->cell2 = $cell2;

        return $this;
    }

    /**
     * Get cell2
     *
     * @return ChildContainer
     */
    public function getCell2()
    {
        return $this->cell2;
    }

    /**
     * Set cell3
     *
     * @param ChildContainer $cell3
     *
     * @return GridX3CellsWidget
     */
    public function setCell3(ChildContainer $cell3 = null)
    {
        $this->cell3 = $cell3;

        return $this;
    }

    /**
     * Get cell3
     *
     * @return ChildContainer
     */
    public function getCell3()
    {
        return $this->cell3;
    }

    public function getCell(int $cell) {
        if (in_array($cell, [1,2,3])) {
            return $this->{"cell{$cell}"};
        }
    }

    /**
     * Set container
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container
     *
     * @return GridX3CellsWidget
     */
    public function setContainer(\MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container = null)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container
     *
     * @return \MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer
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
            'cell1' => $this->cell1->serialize(),
            'cell2' => $this->cell2->serialize(),
            'cell3' => $this->cell3->serialize()
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
        $serializedCell1 = $versionData['cell1'];
        $serializedCell2 = $versionData['cell2'];
        $serializedCell3 = $versionData['cell3'];

        $cell1 = new ChildContainer();
        $cell1->unserialize($serializedCell1);
        $cell1->setWidget($this);
        $this->cell1 = $cell1;

        $cell2 = new ChildContainer();
        $cell2->unserialize($serializedCell2);
        $cell2->setWidget($this);
        $this->cell2 = $cell2;

        $cell3 = new ChildContainer();
        $cell3->unserialize($serializedCell3);
        $cell3->setWidget($this);
        $this->cell3 = $cell3;
    }
}
