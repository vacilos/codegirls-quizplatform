<?php

namespace Vacilos\QuizBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Vacilos\QuizBundle\Entity\QuizQuestion;
use Vacilos\QuizBundle\Form\QuizQuestionType;

/**
 * QuizQuestion controller.
 *
 */
class QuizQuestionController extends Controller
{

    /**
     * Lists all QuizQuestion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('VacilosQuizBundle:QuizQuestion')->findAll();

        return $this->render('VacilosQuizBundle:QuizQuestion:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new QuizQuestion entity.
     *
     */
    public function createAction(Request $request, $quizId)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('VacilosQuizBundle:Quiz')->find($quizId);

        $entity = new QuizQuestion();
        $entity->setQuiz($quiz);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $qq = $quiz->getQuizQuestions();
            $currentQuestion = $entity->getQuestion();
            if(sizeof($qq) > 0) {
                foreach($qq as $quest) {
                    if($quest->getId() == $currentQuestion->getId()) {
                        $form->get('question')->addError(new FormError('Η ερώτηση έχει ήδη προστεθεί'));
                        return $this->render('VacilosQuizBundle:QuizQuestion:new.html.twig', array(
                            'entity' => $entity,
                            'form'   => $form->createView(),
                        ));
                    }
                }
            }

            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quiz_show', array('id' => $quiz->getId())));
        }

        return $this->render('VacilosQuizBundle:QuizQuestion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a QuizQuestion entity.
     *
     * @param QuizQuestion $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(QuizQuestion $entity)
    {
        $form = $this->createForm(new QuizQuestionType(), $entity, array(
            'action' => $this->generateUrl('quizquestion_create', array('quizId'=> $entity->getQuiz()->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new QuizQuestion entity.
     *
     */
    public function newAction($quizId)
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository('VacilosQuizBundle:Quiz')->find($quizId);

        $entity = new QuizQuestion();
        $entity->setQuiz($quiz);
        $form   = $this->createCreateForm($entity);

        return $this->render('VacilosQuizBundle:QuizQuestion:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a QuizQuestion entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:QuizQuestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QuizQuestion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VacilosQuizBundle:QuizQuestion:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing QuizQuestion entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:QuizQuestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QuizQuestion entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('VacilosQuizBundle:QuizQuestion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a QuizQuestion entity.
    *
    * @param QuizQuestion $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(QuizQuestion $entity)
    {
        $form = $this->createForm(new QuizQuestionType(), $entity, array(
            'action' => $this->generateUrl('quizquestion_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing QuizQuestion entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('VacilosQuizBundle:QuizQuestion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find QuizQuestion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('quizquestion_edit', array('id' => $id)));
        }

        return $this->render('VacilosQuizBundle:QuizQuestion:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a QuizQuestion entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('VacilosQuizBundle:QuizQuestion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find QuizQuestion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('quizquestion'));
    }

    /**
     * Creates a form to delete a QuizQuestion entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quizquestion_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
