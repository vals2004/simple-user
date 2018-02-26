<?php

namespace SimpleUser\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SimpleUser\Form\PasswordResetType;
use SimpleUser\Interfaces\SimpleUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PasswordResetController extends Controller
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param string $passwordResetHash
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EntityManagerInterface $em, string $passwordResetHash) {
        /** @var SimpleUserInterface $user */
        $user = $em->getRepository($this->getParameter('simple_user.user_class'))
            ->findOneBy(['passwordResetHash' => $passwordResetHash]);

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em->flush();

            $token = new UsernamePasswordToken(
                $user,
                null,
                $this->getParameter('simple_user.firewall_name'),
                $user->getRoles()
            );

            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set(
                '_security_' . $this->getParameter('simple_user.firewall_name'),
                serialize($token)
            );

            return $this->redirectToRoute($this->getParameter('simple_user.redirect_after_login'));
        }

        return $this->render('@SimpleUser/PasswordReset/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}