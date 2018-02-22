<?php

namespace SimpleUser\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use SimpleUser\Form\UserType;

class SecurityController extends Controller
{
    /**
     * @param Request $request
     */
    public function login(Request $request)
    {
        $session = $request->getSession();

        $lastUsername = $session->get(Security::LAST_USERNAME) ?? '';
        $error = $session->get(Security::AUTHENTICATION_ERROR);
        $session->remove(Security::AUTHENTICATION_ERROR);
        $form = $this->createForm(UserType::class);

        return $this->render('@SimpleUser/Security/login.html.twig',[
            'error' => $error,
            'form' => $form->createView(),
        ]);

    }

    public function login_check()
    {
    }

    public function logoutAction()
    {
    }

}
