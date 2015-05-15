<?php

namespace Skahr\SaltCityBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CommentType extends AbstractType
{
	protected $session;
	public function __construct(Session $session){$this->session=$session;}
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array('label' => 'Ваше имя'))
            ->add('usermessage', 'textarea', array('label' => 'Отзыв'))
			;
			
			
			
		$builder
			->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event){
        
				$form=$event->getForm();
				
				if($this->session->get('login')) {
			
            $form->add('adminreply', 'textarea', array('label' => 'Ответ на отзыв', 'required' => false));
            $form->add('status', 'integer', array('attr' => array('size' => 1, 'min' => 0, 'max' => 1), 'label' => 'Статус'));}
           }); 
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Skahr\SaltCityBundle\Entity\Comment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'skahr_saltcitybundle_comment';
    }
}
