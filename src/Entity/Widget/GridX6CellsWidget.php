<?php

namespace MoncaretWS\ContentWidgetsBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;
use MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer;

/**
 * Foundation6CellsWidget
 *
 * @ORM\Entity(repositoryClass="MoncaretWS\ContentWidgetsBundle\Repository\WidgetRepository")
 */
class GridX6CellsWidget extends LayoutWidget
{
    /**
     * @var int
     *
     * @ORM\Cell(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinCell(name="cell1_container_id", referencedCellName="id")
     */
    protected $cell1;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinCell(name="cell2_container_id", referencedCellName="id")
     */
    protected $cell2;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinCell(name="cell3_container_id", referencedCellName="id")
     */
    private $cell3;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinCell(name="cell4_container_id", referencedCellName="id")
     */
    private $cell4;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinCell(name="cell5_container_id", referencedCellName="id")
     */
    private $cell5;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinCell(name="cell6_container_id", referencedCellName="id")
     */
    private $cell6;


    public function __construct() {
        $this->template = '@content_widgets/widget_templates/grid_x_6_cells.widget.html.twig';
    }

    public function getChildContainers()
    {
        return [
            'cell1' => $this->getCell1(),
            'cell2' => $this->getCell2(),
            'cell3' => $this->getCell3(),
            'cell4' => $this->getCell4(),
            'cell5' => $this->getCell5(),
            'cell6' => $this->getCell6()
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
     * @return GridX6CellsWidget
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
     * @return GridX6CellsWidget
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
     * @return GridX6CellsWidget
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
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell1
     *
     * @return GridX6CellsWidget
     */
    public function setCell1(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell1 = null)
    {
        $this->cell1 = $cell1;

        return $this;
    }

    /**
     * Get cell1
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell1()
    {
        return $this->cell1;
    }

    /**
     * Set cell2
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell2
     *
     * @return GridX6CellsWidget
     */
    public function setCell2(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell2 = null)
    {
        $this->cell2 = $cell2;

        return $this;
    }

    /**
     * Get cell2
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell2()
    {
        return $this->cell2;
    }

    /**
     * Set cell3
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell3
     *
     * @return GridX6CellsWidget
     */
    public function setCell3(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell3 = null)
    {
        $this->cell3 = $cell3;

        return $this;
    }

    /**
     * Get cell3
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell3()
    {
        return $this->cell3;
    }

    /**
     * Set cell4
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell4
     *
     * @return GridX6CellsWidget
     */
    public function setCell4(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell4 = null)
    {
        $this->cell4 = $cell4;

        return $this;
    }

    /**
     * Get cell4
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell4()
    {
        return $this->cell4;
    }

    /**
     * Set cell3
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell3
     *
     * @return GridX6CellsWidget
     */
    public function setCell5(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell5 = null)
    {
        $this->cell5 = $cell5;

        return $this;
    }

    /**
     * Get cell3
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell5()
    {
        return $this->cell5;
    }

    /**
     * Set cell4
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell4
     *
     * @return GridX6CellsWidget
     */
    public function setCell6(\MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell6 = null)
    {
        $this->cell6 = $cell6;

        return $this;
    }

    /**
     * Get cell4
     *
     * @return \MoncaretWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell6()
    {
        return $this->cell6;
    }

    public function getCell(int $cell) {
        if (in_array($cell, [1,2,3,4,5,6])) {
            return $this->{"cell{$cell}"};
        }
    }

    /**
     * Set container
     *
     * @param \MoncaretWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container
     *
     * @return GridX6CellsWidget
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
            'cell1' => $this->cell1->serialize(),
            'cell2' => $this->cell2->serialize(),
            'cell3' => $this->cell3->serialize(),
            'cell4' => $this->cell4->serialize(),
            'cell5' => $this->cell5->serialize(),
            'cell6' => $this->cell6->serialize()
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
        $serializedCell4 = $versionData['cell4'];
        $serializedCell5 = $versionData['cell5'];
        $serializedCell6 = $versionData['cell6'];

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

        $cell4 = new ChildContainer();
        $cell4->unserialize($serializedCell4);
        $cell4->setWidget($this);
        $this->cell4 = $cell4;

        $cell5 = new ChildContainer();
        $cell5->unserialize($serializedCell5);
        $cell5->setWidget($this);
        $this->cell5 = $cell5;

        $cell6 = new ChildContainer();
        $cell6->unserialize($serializedCell6);
        $cell6->setWidget($this);
        $this->cell6 = $cell6;
    }
}
