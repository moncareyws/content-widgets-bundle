<?php

/*
 * Created by Samuel Moncarey
 * 29/06/2017
 */

namespace MoncaretWS\ContentWidgetsBundle\Form\Type;


use Exercise\HTMLPurifierBundle\Form\HTMLPurifierTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrumbowygEditorType extends AbstractType {

    /**
     * @var HTMLPurifierTransformer
     */
    private $htmlPurifier;

    /**
     * TrumbowygEditorType constructor.
     *
     * @param HTMLPurifierTransformer $htmlPurifier
     */
    public function __construct(HTMLPurifierTransformer $htmlPurifier) {
        $this->htmlPurifier = $htmlPurifier;
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->addModelTransformer($this->htmlPurifier);
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(['empty_data' => '<p><br></p>']);
    }

    /**
     * Returns the name of the parent type.
     *
     * @return string|null The name of the parent type if any, null otherwise
     */
    public function getParent() {
        return TextType::class;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName() {
        return 'trumbowyg_editor';
    }

}