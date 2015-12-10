<?php

namespace Vacilos\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vacilos\QuizBundle\Entity\UserQuizAnswer;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $security_context = $this->get('security.context');

        $isAdmin = $security_context->isGranted('ROLE_ADMIN');
        if ($isAdmin) {
            return $this->render('VacilosQuizBundle:Default:admin.html.twig');
        } else {
            // here we write the list of quizes that we have started and statistics

            return $this->render('VacilosQuizBundle:Default:index.html.twig');
        }

    }

    public function quizListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository("VacilosQuizBundle:Quiz")->findAll();

        return $this->render('VacilosQuizBundle:Default:quizList.html.twig', array(
            'quiz' => $quiz
        ));

    }

    public function myQuizAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository("VacilosQuizBundle:UserQuiz")->findByUser($user);

        return $this->render('VacilosQuizBundle:Default:myQuiz.html.twig', array(
            'quiz' => $quiz
        ));

    }

    public function enterQuiz($quizId)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository("VacilosQuizBundle:UserQuiz")->findOneBy(array(
            'user' => $user->getId(),
            'quiz' => $quizId
        ));

        $repository = $this->getDoctrine()
            ->getRepository('VacilosQuizBundle:UserQuizAnswer');

        $query = $repository->createQueryBuilder('uqa')
            ->join('uqa.userQuiz', 'uq')
            ->where('uq.quiz = :quizId')
            ->andWhere('uqa.user = :user')
            ->setParameter('quizId', $quizId)
            ->setParameter('user', $user->getId())
            ->getQuery();

        $userQuizAnswers = $query->getResult();

        $bannedList = array();
        foreach ($userQuizAnswers as $uqa) {
            $bannedList[] = $uqa->getQuizQuestion()->getQuestion()->getId();
        }

        $repository = $this->getDoctrine()
            ->getRepository('VacilosQuizBundle:QuizQuestion');

        $query = $repository->createQueryBuilder('qq')
            ->join('qq.question', 'q')
            ->where('qq.quiz = :quizId')
            ->andWhere('q.id NOT IN (:banned)')
            ->setParameter('quizId', $quizId)
            ->setParameter('banned', $bannedList)
            ->orderBy('qq.ordering', 'ASC')
            ->getQuery();

        $pickAQuestion = $query->getOneOrNullResult();

        if ($pickAQuestion == null) {
            // finished the quiz
            $quiz->setStatus(2);
            return $this->render('VacilosQuizBundle:Default:presentQuestion.html.twig', array(
                'quiz' => $quiz,
                'question' => $pickAQuestion
            ));
        } else {
            // go to the question
            $quiz->setStatus(1);
            return $this->render('VacilosQuizBundle:Default:presentQuestion.html.twig', array(
                'userQuiz' => $quiz,
                'question' => $pickAQuestion
            ));
        }
    }

    public function answerQuestion($quizId, $questionId, $answerId)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository("VacilosQuizBundle:UserQuiz")->findOneBy(array(
            'user' => $user->getId(),
            'quiz' => $quizId
        ));

        $quizQuestion = $em->getRepository("VacilosQuizBundle:QuizQuestion")->findOneBy(array(
            'question' => $questionId,
            'quiz' => $quizId
        ));

        $answer = $em->getRepository("VacilosQuizBundle:Answer")->find($answerId);

        if(!$quiz || !$quizQuestion || $answer->getQuestion()->getId() != $quizQuestion->getQuestion()->getId()) {
            return $this->createNotFoundException("Δε βρέθηκε αυτό που προσπαθείς να κάνεις");
        }

        $uqa = new UserQuizAnswer();
        $uqa->setQuizquestion($quizQuestion);
        $uqa->setUserquiz($quiz);
        $uqa->setAnswer($answer);
        $em->persist($uqa);
        $quiz->setStatus(1);

        $em->flush();

        return $this->redirectToRoute();
    }


    public function topAction()
    {
        $user = $this->getUser();
        if ($user) {
            return $this->render('VacilosQuizBundle:Default:top.html.twig');
        } else {
            return new Response();
        }
    }

    public function sideAction()
    {
        $user = $this->getUser();
        if ($user) {
            return $this->render('VacilosQuizBundle:Default:side.html.twig');
        } else {
            return new Response();
        }
    }


}
