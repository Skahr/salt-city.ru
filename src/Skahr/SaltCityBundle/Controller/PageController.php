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

    public function saltPhotoAction($id)
    {
        return $this->render('SkahrSaltCityBundle:Page:salt_photo.html.twig', array('id' => $id));
    }

}