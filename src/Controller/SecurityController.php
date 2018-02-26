<?php

namespace SimpleUser\Controller;

use SimpleUser\Traits\RouterTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use SimpleUser\Form\LoginType;

class SecurityController extends Controller
{
    use RouterTrait;

    /**
     * @param Request $request
     */
    public function login(Request $request)
    {
        $session = $request->getSession();
        $lastUsername = $session->get(Security::LAST_USERNAME) ?? '';
        $error = $session->get(Security::AUTHENTICATION_ERROR);
        $session->remove(Security::AUTHENTICATION_ERROR);
        $form = $this->createForm(LoginType::class);

        return $this->render('@SimpleUser/Security/login.html.twig',[
            'error' => $error,
            'form' => $form->createView(),
            'isAllowRegistration' => $this->routeExists('simple_user_registration'),
            'isAllowForgotPassword' => $this->routeExists('simple_user_forgot_password')
        ]);

    }

    public function loginCheck() {
    }

    public function logout()
    {
    }
}
