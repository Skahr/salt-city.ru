<?php

namespace Skahr\SaltCityBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Skahr\SaltCityBundle\Entity\Reset;
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
				//password_hash($data['password'], PASSWORD_DEFAULT)
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
				$rand=substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 20);
				$reset = $em->getRepository('SkahrSaltCityBundle:Reset')->findOneByUserid($entity->getId());
				if(!$reset) {
					$reset = new Reset(); $reset->setUserid($entity->getId());
				}
				$reset->setHash($rand);
				$em->persist($reset);
				//$entity->setPassword(password_hash($rand, PASSWORD_DEFAULT));
            	$em->flush();
				$message = \Swift_Message::newInstance()
        			->setSubject('Соль Сити: Сброс пароля')
        			->setFrom('mail@salt-city.ru')
        			->setTo($data['email'])
        			->setBody(
            			$this->renderView(
                			'SkahrSaltCityBundle:Emails:password_reset.html.twig',
                			array('name' => $entity->getLogin(), 'link' => $this->generateUrl('admin_passresetcheck', array('id' => $entity->getId(), 'hash' => $rand)))
            			),
            			'text/html'
        			)
    			;
    			$this->get('mailer')->send($message);
		
				$this->get('session')->getFlashBag()->set('info', 'Инструкции по смене пароля были высланы на указанный Вами почтовый ящик');
				return $this->redirect($this->generateUrl('admin'));
			}
			else {
				$this->get('session')->getFlashBag()->set('error', 'Указанный Вами почтовый ящик не закреплен ни за одним из пользователей');
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
	public function passResetCheckAction($id, $hash)
	{
		$em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SkahrSaltCityBundle:Reset')->findOneByUserid($id);
		if($entity) {
			if($entity->getHash()==$hash) {
				return $this->render('SkahrSaltCityBundle:Admin:reset.html.twig', array(
				'passresetfin_form' => $this->createPassResetFinForm($id, $hash)->createView()));
			}
		}
		$this->get('session')->getFlashBag()->set('error', 'Ссылка не верна или уже не активна');
		return $this->redirect($this->generateUrl('admin'));
	}
	public function passResetCheckPostAction(Request $request, $id, $hash)
	{
		$passResetFinForm = $this->createPassResetFinForm($id, $hash);
        $passResetFinForm->handleRequest($request);
		if ($passResetFinForm->isValid()) {
			$data=$passResetFinForm->getData();
			$em = $this->getDoctrine()->getManager();
        	$entity = $em->getRepository('SkahrSaltCityBundle:Admin')->findOneById($id);
			$reset = $em->getRepository('SkahrSaltCityBundle:Reset')->findOneByUserid($id);
			if (!$entity) {
            	throw $this->createNotFoundException('Unable to find Admin entity.');
        	}
			$entity->setPassword(password_hash($data['password'], PASSWORD_DEFAULT));
			$em->remove($reset);
			$em->flush();
			$this->get('session')->getFlashBag()->set('info', 'Пароль изменен');
			return $this->redirect($this->generateUrl('admin'));
		}
		$this->get('session')->getFlashBag()->set('error', 'Пароли не совпадают');
		return $this->redirect($this->generateUrl('admin_passresetcheck', array('id' => $id, 'hash' => $hash)));
	}
	private function createPassResetFinForm($id, $hash)
	{
		return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_passresetcheckpost', array('id' => $id, 'hash' => $hash)))
            ->setMethod('POST')
			->add('password', 'repeated', array(
    			'type' => 'password',
				'invalid_message' => 'Пароли должны совпадать',
    			'first_name'      => 'pswrd',
    			'second_name'     => 'confirmpswrd',
				'first_options'  => array('label' => 'Новый пароль'),
    			'second_options' => array('label' => 'Повторите новый пароль'),
))
            ->add('submit', 'submit', array('label' => 'Изменить'))
            ->getForm()
        ;
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
			$this->get('session')->getFlashBag()->set('error', 'Доступ запрещен');
            return $this->redirect($this->generateUrl('admin'));
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
				'invalid_message' => 'Пароли должны совпадать',
    			'first_name'      => 'pass',
    			'second_name'     => 'confirm',
				'first_options'  => array('label' => 'Новый пароль'),
    			'second_options' => array('label' => 'Повторите новый пароль'),
))
            ->add('submit', 'submit', array('label' => 'Изменить'))
            ->getForm()
        ;
    }

}
