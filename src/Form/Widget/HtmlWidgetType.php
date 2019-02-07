<?php

namespace MoncaretWS\ContentWidgetsBundle\Form\Widget;

use Samuel\FdnBundle\Form\Type\TrumbowygEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HtmlWidgetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('html', TrumbowygEditorType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MoncaretWS\ContentWidgetsBundle\Entity\Widget\HtmlWidget'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'samuelmc_fdncontentwidgetsbundle_widget_htmlwidget';
    }


}
