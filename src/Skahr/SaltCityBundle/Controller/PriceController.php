<?php

namespace Skahr\SaltCityBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Skahr\SaltCityBundle\Entity\Price;
use Skahr\SaltCityBundle\Form\PriceType;

/**
 * Price controller.
 *
 */
class PriceController extends Controller
{

    /**
     * Lists all Price entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('SkahrSaltCityBundle:Price')->findAll();

        return $this->render('SkahrSaltCityBundle:Price:index.html.twig', array(
            'entities' => $entities,
        ));
    }
	public function mainPricesAction($max)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                'SELECT p.pricename, p.price, p.priceinfo, p.seats
                FROM SkahrSaltCityBundle:Price p'
            )->setMaxResults($max);
		$entities = $query->getResult();
        
        return $this->render('SkahrSaltCityBundle::pricesblock.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Price entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Price();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('price', array('id' => $entity->getId())));
        }

        return $this->render('SkahrSaltCityBundle:Price:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Price entity.
     *
     * @param Price $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Price $entity)
    {
        $form = $this->createForm(new PriceType(), $entity, array(
            'action' => $this->generateUrl('price_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Создать'));

        return $form;
    }

    /**
     * Displays a form to create a new Price entity.
     *
     */
    public function newAction()
    {
        $entity = new Price();
        $form   = $this->createCreateForm($entity);

        return $this->render('SkahrSaltCityBundle:Price:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Price entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SkahrSaltCityBundle:Price')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Price entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SkahrSaltCityBundle:Price:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Price entity.
    *
    * @param Price $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Price $entity)
    {
        $form = $this->createForm(new PriceType(), $entity, array(
            'action' => $this->generateUrl('price_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Сохранить'));

        return $form;
    }
    /**
     * Edits an existing Price entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SkahrSaltCityBundle:Price')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Price entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('price_edit', array('id' => $id)));
        }

        return $this->render('SkahrSaltCityBundle:Price:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Price entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SkahrSaltCityBundle:Price')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Price entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('price'));
    }

    /**
     * Creates a form to delete a Price entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('price_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Удалить'))
            ->getForm()
        ;
    }
}
