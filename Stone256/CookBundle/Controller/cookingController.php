<?php

namespace Stone256\CookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Stone256\CookBundle\Entity\cooking;
use Stone256\CookBundle\Entity\fridge;
use Stone256\CookBundle\Entity\recipes;
use Stone256\CookBundle\Form\cookingType;

/**
 * cooking controller.
 *
 * @Route("/cooking")
 */
class cookingController extends Controller
{

    /**
     * Lists all cooking entities.
     *
     * @Route("/", name="cooking")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Stone256CookBundle:cooking')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new cooking entity.
     *
     * @Route("/", name="cooking_create")
     * @Method("POST")
     * @Template("Stone256CookBundle:cooking:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new cooking();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $msg = array();
        if ($form->isValid()) {
	    $entity->upload();
	    $entity->updatedTimestamps();
	    
	    //validate csv input
	    $fridge = new fridge($entity->getFridgeAbsolute());
	    $msg = $fridge->isError();
	    if(!$msg){
		//validate json input
		$recipes = new recipes($entity->getRecipes());
		$msg = $recipes->isError();
	    }
	    
	    if(!$msg){
	    /**
	      * check what you can Cook
	      * save result to a db field("food");
	      */
	      $entity->setFood($entity->whatToCook($recipes,$fridge));
	      $em = $this->getDoctrine()->getManager();
	      $em->persist($entity);
	      $em->flush();

	      return $this->redirect($this->generateUrl('cooking_show', array('id' => $entity->getId())));
            }
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'msg' => $msg,
        );
    }

    /**
     * Creates a form to create a cooking entity.
     *
     * @param cooking $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(cooking $entity)
    {
        $form = $this->createForm(new cookingType(), $entity, array(
            'action' => $this->generateUrl('cooking_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new cooking entity.
     *
     * @Route("/new", name="cooking_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new cooking();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'msg' => array(''),
        );
    }

    /**
     * Finds and displays a cooking entity.
     *
     * @Route("/{id}", name="cooking_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Stone256CookBundle:cooking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cooking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing cooking entity.
     *
     * @Route("/{id}/edit", name="cooking_edit")getfrigdeAbsolute
     * @Method("GET")
     * @Template()
     
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Stone256CookBundle:cooking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cooking entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
  */
  
  
    /**
    * Creates a form to edit a cooking entity.
    *
    * @param cooking $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(cooking $entity)
    {
        $form = $this->createForm(new cookingType(), $entity, array(
            'action' => $this->generateUrl('cooking_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing cooking entity.
     *
     * @Route("/{id}", name="cooking_update")
     * @Method("PUT")
     * @Template("Stone256CookBundle:cooking:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Stone256CookBundle:cooking')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cooking entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
	    $entity->upload();
            $em->flush();

            return $this->redirect($this->generateUrl('cooking_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a cooking entity.
     *
     * @Route("/{id}", name="cooking_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Stone256CookBundle:cooking')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find cooking entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cooking'));
    }

    /**
     * Creates a form to delete a cooking entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('cooking_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
