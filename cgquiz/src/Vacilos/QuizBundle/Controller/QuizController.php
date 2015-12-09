<?php

namespace Vacilos\QuizBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Vacilos\QuizBundle\Entity\Quiz;
use Vacilos\QuizBundle\Form\QuizType;

/**
 * Quiz controller.
 *
 */
class QuizController extends Controller
{

    /**
     * Lists all Quiz entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VacilosQuizBundle:Quiz')->findAll();

        return $this->render('VacilosQuizBundle:Quiz:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Quiz entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Quiz();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quiz_show', array('id' => $entity->getId())));
        }

        return $this->render('VacilosQuizBundle:Quiz:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Quiz entity.
     *
     * @param Quiz $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Quiz $entity)
    {
        $form = $this->createForm(new QuizType(), $entity, array(
            'action' => $this->generateUrl('quiz_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Quiz entity.
     *
     */
    public function newAction()
    {
        $entity = new Quiz();
        $form   = $this->createCreateForm($entity);

        return $this->render('VacilosQuizBundle:Quiz:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Quiz entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:Quiz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VacilosQuizBundle:Quiz:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Quiz entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:Quiz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VacilosQuizBundle:Quiz:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Quiz entity.
    *
    * @param Quiz $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Quiz $entity)
    {
        $form = $this->createForm(new QuizType(), $entity, array(
            'action' => $this->generateUrl('quiz_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Quiz entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:Quiz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quiz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('quiz_edit', array('id' => $id)));
        }

        return $this->render('VacilosQuizBundle:Quiz:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Quiz entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VacilosQuizBundle:Quiz')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Quiz entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('quiz'));
    }

    /**
     * Creates a form to delete a Quiz entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quiz_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
