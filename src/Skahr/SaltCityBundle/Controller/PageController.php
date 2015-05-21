<?php
namespace Skahr\SaltCityBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:index.html.twig');
    }
    public function contactsAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:contacts.html.twig');
    }
    public function saltAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:salt.html.twig');
    }
    public function saltPhotoAction($id)
    {
        return $this->render('SkahrSaltCityBundle:Page:salt_photo.html.twig', array('id' => $id));
    }
    public function saltRulesAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:salt_rules.html.twig');
    }
	public function saltIndicationsAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:salt_indications.html.twig');
    }
    public function saltContraindicationsAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:salt_contr.html.twig');
    }
    public function saltCertAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:salt_cert.html.twig');
    }
    public function loginAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:login.html.twig');
    }
	public function adminAction()
    {
        return $this->render('SkahrSaltCityBundle:Page:admin.html.twig');
    }
    public function checkAction()
    {
        $session = new Session();
        $session->start();
        if(isset($_POST['login'])) {
            if($_POST['email']=="salt@master.ru") {
                
                $session->set('login', $_POST['email']);
				$this->get('session')->getFlashBag()->set('info', 'Добро пожаловать, '.$session->get('login'));
				return $this->redirect($this->generateUrl('admin'));
            }
			else {
				$this->get('session')->getFlashBag()->set('error', 'Неверные учетные данные');
				return $this->redirect($this->generateUrl('SkahrSaltCityBundle_login'));
			}
        }
		else {return $this->redirect($this->generateUrl('SkahrSaltCityBundle_login'));}
    }
    public function logoutAction() {
        $session = new Session();
        $session->start();
        if($session->get('login')) {
            $session->remove('login');
        }
        return $this->redirect($this->generateUrl('SkahrSaltCityBundle_homepage'));
    }
}