<?php

namespace Vacilos\QuizBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Vacilos\QuizBundle\Entity\Answer;
use Vacilos\QuizBundle\Form\AnswerType;

/**
 * Answer controller.
 *
 */
class AnswerController extends Controller
{

    /**
     * Lists all Answer entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VacilosQuizBundle:Answer')->findAll();

        return $this->render('VacilosQuizBundle:Answer:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Answer entity.
     *
     */
    public function createAction(Request $request, $questionId)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('VacilosQuizBundle:Question')->find($questionId);

        $entity = new Answer();
        $entity->setQuestion($question);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $iscorrect = $entity->getIsCorrect();
            if($iscorrect) {
                foreach($question->getAnswers() as $ans) {
                    if($ans->getIsCorrect()) {
                        $form->get('isCorrect')->addError(new FormError('Η ερώτηση έχει ήδη μια σωστή απάντηση'));
                        return $this->render('VacilosQuizBundle:Answer:new.html.twig', array(
                            'entity' => $entity,
                            'form'   => $form->createView(),
                        ));
                    }
                }
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $question->getId())));
        }

        return $this->render('VacilosQuizBundle:Answer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Answer entity.
     *
     * @param Answer $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Answer $entity)
    {
        $form = $this->createForm(new AnswerType(), $entity, array(
            'action' => $this->generateUrl('answer_create', array('questionId' => $entity->getQuestion()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Answer entity.
     *
     */
    public function newAction($questionId)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('VacilosQuizBundle:Question')->find($questionId);
        $entity = new Answer();
        $entity->setQuestion($question);
        $form   = $this->createCreateForm($entity);

        return $this->render('VacilosQuizBundle:Answer:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Answer entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VacilosQuizBundle:Answer:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Answer entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VacilosQuizBundle:Answer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Answer entity.
    *
    * @param Answer $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Answer $entity)
    {
        $form = $this->createForm(new AnswerType(), $entity, array(
            'action' => $this->generateUrl('answer_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Answer entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('answer_edit', array('id' => $id)));
        }

        return $this->render('VacilosQuizBundle:Answer:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Answer entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VacilosQuizBundle:Answer')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Answer entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('answer'));
    }

    /**
     * Creates a form to delete a Answer entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('answer_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
