<?php

/*
 * Created by Samuel Moncarey
 * 16/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Entity\Widget;

use Doctrine\ORM\Mapping as ORM;


/**
 * ContentWidget
 *
 * @ORM\Entity(repositoryClass="MoncaretWS\ContentWidgetsBundle\Repository\WidgetRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 */
abstract class ContentWidget extends Widget {

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
     * @ORM\Column(name="form_type_class", type="string", length=255)
     */
    protected $formTypeClass;

    /**
     * Set formTypeClass
     *
     * @param string $formTypeClass
     *
     * @return ContentWidget
     */
    public function setFormTypeClass($formTypeClass)
    {
        $this->formTypeClass = $formTypeClass;

        return $this;
    }

    /**
     * Get formTypeClass
     *
     * @return string
     */
    public function getFormTypeClass()
    {
        return $this->formTypeClass;
    }
}
