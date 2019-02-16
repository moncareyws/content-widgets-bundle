<?php

namespace MoncareyWS\ContentWidgetsBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;
Use MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer;
use MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer;

/**
 * Foundation2CellsWidget
 *
 * @ORM\Entity(repositoryClass="MoncareyWS\ContentWidgetsBundle\Repository\WidgetRepository")
 */
class GridX2CellsWidget extends LayoutWidget
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
     * @ORM\OneToOne(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinColumn(name="cell1_container_id", referencedColumnName="id")
     */
    protected $cell1;

    /**
     * @var ChildContainer
     *
     * @ORM\OneToOne(targetEntity="MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer", orphanRemoval=true)
     * @ORM\JoinColumn(name="cell2_container_id", referencedColumnName="id")
     */
    protected $cell2;


    public function __construct() {

        if ($this->template === null) $this->template = '@content_widgets/widget_templates/grid_x_2_cells.widget.html.twig';
    }

    public function getChildContainers(): array
    {
        return [
            'cell1' => $this->getCell1(),
            'cell2' => $this->getCell2()
        ];
    }

    public function getParent(): WidgetContainer
    {
        $this->getContainer();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return GridX2CellsWidget
     */
    public function setTemplate($template): GridX2CellsWidget
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return GridX2CellsWidget
     */
    public function setPosition($position): GridX2CellsWidget
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * Set hidden
     *
     * @param boolean $hidden
     *
     * @return GridX2CellsWidget
     */
    public function setHidden($hidden): GridX2CellsWidget
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return boolean
     */
    public function getHidden(): bool
    {
        return $this->hidden;
    }

    /**
     * Set cell1
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell1
     *
     * @return GridX2CellsWidget
     */
    public function setCell1(\MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell1 = null): GridX2CellsWidget
    {
        $this->cell1 = $cell1;

        return $this;
    }

    /**
     * Get cell1
     *
     * @return \MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell1(): ChildContainer
    {
        return $this->cell1;
    }

    /**
     * Set cell2
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell2
     *
     * @return GridX2CellsWidget
     */
    public function setCell2(\MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer $cell2 = null): GridX2CellsWidget
    {
        $this->cell2 = $cell2;

        return $this;
    }

    /**
     * Get cell2
     *
     * @return \MoncareyWS\ContentWidgetsBundle\Entity\Container\ChildContainer
     */
    public function getCell2(): ChildContainer
    {
        return $this->cell2;
    }

    public function getCell(int $cell): ChildContainer
    {
        if (in_array($cell, [1,2])) {
            return $this->{"cell{$cell}"};
        }
    }

    /**
     * Set container
     *
     * @param \MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer $container
     *
     * @return GridX2CellsWidget
     */
    public function setContainer(WidgetContainer $container = null): GridX2CellsWidget
    {
        $this->container = $container;

        return $this;
    }

    /**
     * Get container
     *
     * @return \MoncareyWS\ContentWidgetsBundle\Entity\Container\WidgetContainer
     */
    public function getContainer(): WidgetContainer
    {
        return $this->container;
    }

    public function serialize(): string
    {
        $serializable = [
            'id' => $this->id,
            'template' => $this->template,
            'position' => $this->position,
            'hidden' => $this->hidden,
            'cell1' => $this->cell1->serialize(),
            'cell2' => $this->cell2->serialize(),
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

        $cell1 = new ChildContainer();
        $cell1->unserialize($serializedCell1);
        $cell1->setWidget($this);
        $this->cell1 = $cell1;

        $cell2 = new ChildContainer();
        $cell2->unserialize($serializedCell2);
        $cell2->setWidget($this);
        $this->cell2 = $cell2;
    }
}
