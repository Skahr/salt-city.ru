<?php

namespace Skahr\SaltCityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PriceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pricename', 'text', array('label' => 'Название тарифа'))
            ->add('price', 'integer', array('label' => 'Стоимость за сеанс'))
            ->add('priceinfo', 'textarea', array('label' => 'Подробности', 'required' => false))
			->add('seats', 'integer', array('label' => 'Кол-во мест', 'attr' => array('value' => 1, 'min' => 1)))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Skahr\SaltCityBundle\Entity\Price'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'skahr_saltcitybundle_price';
    }
}
