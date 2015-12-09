<?php

namespace Vacilos\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $security_context = $this->get('security.context');

        $isAdmin = $security_context->isGranted('ROLE_ADMIN');
        if($isAdmin) {
            return $this->render('VacilosQuizBundle:Default:admin.html.twig');
        } else {
            return $this->render('VacilosQuizBundle:Default:index.html.twig');
        }

    }


    public function topAction() {
        $user = $this->getUser();
        if ($user) {
            return $this->render('VacilosQuizBundle:Default:top.html.twig');
        } else {
            return new Response();
        }
    }


}
