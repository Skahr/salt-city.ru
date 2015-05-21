<?php

namespace Skahr\SaltCityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Skahr\SaltCityBundle\Entity\Admin;
use Skahr\SaltCityBundle\Form\AdminType;

/**
 * Admin controller.
 *
 */
class AdminController extends Controller
{

    /**
     * Lists all Admin entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SkahrSaltCityBundle:Admin')->findAll();
		$entity = new Admin();
		$passChangeForm = $this->createPassChangeForm($entity);
		$loginForm = $this->createLoginForm();
		return $this->render('SkahrSaltCityBundle:Admin:index.html.twig', array(
            'entities' => $entities,
			'passchange_form' => $passChangeForm->createView(),
			'login_form' => $loginForm->createView(),
			'passreset_form' => $this->createPassResetForm()->createView()
        ));
    }
	public function loginAction(Request $request)
	{
		
		$loginForm = $this->createLoginForm();
        $loginForm->handleRequest($request);
        if ($loginForm->isValid()) {
			$data=$loginForm->getData();
			
			if ($this->passCheck($data['login'], $data['password'])) {
				$this->get('session')->set('login', $data['login']);
				$this->get('session')->getFlashBag()->set('info', 'Добро пожаловать, '.$data['login']);
			}
			else {
				$this->get('session')->getFlashBag()->set('error', 'Неверные учетные данные');
			}
			
			return $this->redirect($this->generateUrl('admin'));
		}
		return $this->render('SkahrSaltCityBundle:Admin:index.html.twig', array(
            'entities'      => $entity,
            'passchange_form' => $passChangeForm->createView(),
			'login_form' => $loginForm->createView(),
			'passreset_form' => $this->createPassResetForm()->createView()
        ));
	}
	private function createLoginForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_login'))
            ->setMethod('POST')
			->add('login', 'text', array('label' => 'Логин'))
			->add('password', 'password', array('label' => 'Пароль'))
            ->add('submit', 'submit', array('label' => 'Войти'))
            ->getForm()
        ;
    }
	public function logoutAction()
	{
		if($this->get('session')->get('login')) {
            $this->get('session')->remove('login');
        }
        return $this->redirect($this->generateUrl('SkahrSaltCityBundle_homepage'));
	}
	public function passResetAction(Request $request)
	{
		$passResetForm = $this->createPassResetForm();
        $passResetForm->handleRequest($request);
        if ($passResetForm->isValid()) {
			$data=$passResetForm->getData();
			$em = $this->getDoctrine()->getManager();
        	$entity = $em->getRepository('SkahrSaltCityBundle:Admin')->findOneByEmail($data['email']);
			if($entity) {
				$rand=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
				$entity->setPassword(password_hash($rand, PASSWORD_DEFAULT));
            	$em->flush();
				$message = \Swift_Message::newInstance()
        			->setSubject('Соль Сити: Сброс пароля')
        			->setFrom('w1nterx44@gmail.com')
        			->setTo($data['email'])
        			->setBody(
            			$this->renderView(
                			'SkahrSaltCityBundle:Emails:password_reset.html.twig',
                			array('name' => $entity->getLogin(), 'password' => $rand)
            			),
            			'text/html'
        			)
    			;
    			$this->get('mailer')->send($message);
		
				$this->get('session')->getFlashBag()->set('info', 'Новый пароль был выслан на указанный Вами ящик');
				//return $this->redirect($this->generateUrl('admin'));
			}
			else {
				$this->get('session')->getFlashBag()->set('error', 'Указанный Вами ящик не закреплен ни за одним из пользователей');
				return $this->redirect($this->generateUrl('admin'));
			}
		}
		return $this->render('SkahrSaltCityBundle:Admin:index.html.twig', array(
            'entities'      => $entity,
            'passchange_form' => $this->createPassChangeForm($entity)->createView(),
			'login_form' => $this->createLoginForm()->createView(),
			'passreset_form' => $this->createPassResetForm()->createView()
        ));
	}
	private function createPassResetForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_passreset'))
            ->setMethod('POST')
			->add('email', 'email', array('label' => 'E-mail'))
            ->add('submit', 'submit', array('label' => 'ОК'))
            ->getForm()
        ;
    }
	private function passCheck($login, $pass)
	{
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('SkahrSaltCityBundle:Admin')->findOneByLogin($login);
		if ($entity) {
			if (password_verify($pass, $entity->getPassword())) { return true; }}
		return false;
	}
	public function passChangeAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$id=$this->get('session')->get('login');
        $entity = $em->getRepository('SkahrSaltCityBundle:Admin')->findOneByLogin($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Admin entity.');
        }
		
        $passChangeForm = $this->createPassChangeForm($entity);
        $passChangeForm->handleRequest($request);
        if ($passChangeForm->isValid()) {
			$data=$passChangeForm->getData();
			if ($this->passCheck($this->get('session')->get('login'), $data['old_password'])) {
				$entity->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
            	$em->flush();
				$this->get('session')->getFlashBag()->set('info', 'Пароль изменен');
			}
			else {
				$this->get('session')->getFlashBag()->set('error', 'Неверный пароль');
			}
            return $this->redirect($this->generateUrl('admin'));
        }

        return $this->render('SkahrSaltCityBundle:Admin:index.html.twig', array(
            'entities'      => $entity,
            'passchange_form' => $passChangeForm->createView(),
			'login_form' => $this->createLoginForm()->createView()
        ));
	}
	private function createPassChangeForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_passchange'))
            ->setMethod('POST')
			->add('old_password', 'password', array('label' => 'Старый пароль'))
			->add('password', 'repeated', array(
    			'type' => 'password',
				'invalid_message' => 'Passwords have to be equal.',
    			'first_name'      => 'pass',
    			'second_name'     => 'confirm',
				'first_options'  => array('label' => 'Новый пароль'),
    			'second_options' => array('label' => 'Повторите новый пароль'),
))
            ->add('submit', 'submit', array('label' => 'Изменить'))
            ->getForm()
        ;
    }
    /**
     * Creates a new Admin entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Admin();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_show', array('id' => $entity->getId())));
        }

        return $this->render('SkahrSaltCityBundle:Admin:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Admin entity.
     *
     * @param Admin $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Admin $entity)
    {
        $form = $this->createForm(new AdminType(), $entity, array(
            'action' => $this->generateUrl('admin_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Admin entity.
     *
     */
    public function newAction()
    {
        $entity = new Admin();
        $form   = $this->createCreateForm($entity);

        return $this->render('SkahrSaltCityBundle:Admin:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Admin entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SkahrSaltCityBundle:Admin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Admin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SkahrSaltCityBundle:Admin:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Admin entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SkahrSaltCityBundle:Admin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Admin entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SkahrSaltCityBundle:Admin:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Admin entity.
    *
    * @param Admin $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Admin $entity)
    {
        $form = $this->createForm(new AdminType(), $entity, array(
            'action' => $this->generateUrl('admin_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Admin entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SkahrSaltCityBundle:Admin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Admin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('admin_edit', array('id' => $id)));
        }

        return $this->render('SkahrSaltCityBundle:Admin:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Admin entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SkahrSaltCityBundle:Admin')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Admin entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin'));
    }

    /**
     * Creates a form to delete a Admin entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
