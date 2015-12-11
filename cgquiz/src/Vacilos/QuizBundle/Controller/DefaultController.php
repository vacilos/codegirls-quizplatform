<?php

namespace Vacilos\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Vacilos\QuizBundle\Entity\UserQuizAnswer;
use Vacilos\QuizBundle\Entity\UserQuiz;

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
        $quizs = $em->getRepository("VacilosQuizBundle:Quiz")->findAll();

        return $this->render('VacilosQuizBundle:Default:quizList.html.twig', array(
            'quizs' => $quizs
        ));

    }

    public function viewQuizAction($slug)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository("VacilosQuizBundle:Quiz")->findOneBySlug($slug);

        $userQuiz = $em->getRepository("VacilosQuizBundle:UserQuiz")->findOneBy(array(
            'quiz' => $quiz->getId(),
            'user' => $user->getId()
        ));

        return $this->render('VacilosQuizBundle:Default:viewQuiz.html.twig', array(
            'quiz' => $quiz,
            'userQuiz' => $userQuiz
        ));

    }
    public function resetQuizAction($slug)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository("VacilosQuizBundle:Quiz")->findOneBySlug($slug);

        $userQuiz = $em->getRepository("VacilosQuizBundle:UserQuiz")->findOneBy(array(
            'quiz' => $quiz->getId(),
            'user' => $user->getId()
        ));

        $em->remove($userQuiz);
        $em->flush();

        return $this->redirectToRoute('userquiz_enter', array('quizId' => $quiz->getId()));

    }

    public function myQuizAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $quiz = $em->getRepository("VacilosQuizBundle:UserQuiz")->findByUser($user);

        return $this->render('VacilosQuizBundle:Default:myQuiz.html.twig', array(
            'quizs' => $quiz
        ));

    }

    public function enterQuizAction($quizId)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $quiz = $em->getRepository("VacilosQuizBundle:Quiz")->find($quizId);

        $userquiz = $em->getRepository("VacilosQuizBundle:UserQuiz")->findOneBy(array(
            'user' => $user->getId(),
            'quiz' => $quiz->getId()
        ));


        if(!$userquiz) {
            $userquiz = new UserQuiz();
            $userquiz->setQuiz($quiz);
            $userquiz->setUser($user);
            $em->persist($userquiz);
            $em->flush();
        }

        $repository = $this->getDoctrine()
            ->getRepository('VacilosQuizBundle:UserQuizAnswer');

        $query = $repository->createQueryBuilder('uqa')
            ->join('uqa.userquiz', 'uq')
            ->where('uq.quiz = :quizId')
            ->andWhere('uq.user = :user')
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

        $prepare = $repository->createQueryBuilder('qq')
            ->join('qq.question', 'q')
            ->where('qq.quiz = :quizId')
            ->setParameter('quizId', $quizId)
            ->orderBy('qq.ordering', 'ASC')
            ->setMaxResults(1);

        if(sizeof($bannedList) > 0) {
            $prepare = $prepare
                ->andWhere('q.id NOT IN (:banned)')
                ->setParameter('banned', $bannedList);
        }

        $query = $prepare->getQuery();

        $pickAQuestion = $query->getOneOrNullResult();

        if ($pickAQuestion == null) {
            // finished the quiz
            $userquiz->setStatus(2);
            $em->persist($userquiz);
            $em->flush();

            $correct = 0;
            foreach($userQuizAnswers as $answer) {
                if($answer->getAnswer()->getIsCorrect() == 1) {
                    ++$correct;
                }
            }

            return $this->render('VacilosQuizBundle:Default:quizFinish.html.twig', array(
                'quiz' => $quiz,
                'answers' => $userQuizAnswers,
                'correct' => $correct
            ));
        } else {

            // go to the question
            $userquiz->setStatus(1);
            $em->persist($userquiz);
            $em->flush();
            return $this->render('VacilosQuizBundle:Default:presentQuestion.html.twig', array(
                'userQuiz' => $userquiz,
                'question' => $pickAQuestion
            ));
        }
    }

    public function answerQuestionAction($quizId, $questionId, $answerId)
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

        $correctAns = null;
        $correct = 0;
        foreach($quizQuestion->getQuestion()->getAnswers() as $ans) {
            if($ans->getIsCorrect()) {
                $correctAns = $ans;
                if($ans->getId() == $answer->getId()) {
                    $correct = 1;
                }
            }
        }
        return $this->render('VacilosQuizBundle:Default:answer.html.twig', array(
            'quizId' => $quizId,
            'correctAns' => $correctAns,
            'correct' => $correct,
            'userQuiz' => $quiz,
            'answer' => $answer

        ));

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
