<?php

namespace Skahr\SaltCityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SaleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salestext', 'text', array('label' => 'Текст события'))
            ->add('status', 'choice', array(
				'choices' => array('0' => 'Не показывать на главной', '1' => 'Показывать на главной'),
				'label' => 'Статус'))
            ->add('cat', 'choice', array(
				'choices' => array('0' => 'Новости', '1' => 'Акция'),
				'label' => 'Тип события'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Skahr\SaltCityBundle\Entity\Sale'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'skahr_saltcitybundle_sale';
    }
}
