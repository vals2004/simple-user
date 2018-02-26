<?php

namespace SimpleUser\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SimpleUser\Form\EmailType;
use SimpleUser\Form\PasswordResetType;
use SimpleUser\Helpers\HashHelper;
use SimpleUser\Interfaces\SimpleUserInterface;
use SimpleUser\Service\MailerBuilderService;
use SimpleUser\Traits\RouterTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PasswordResetController extends Controller
{
    use RouterTrait;

    /**
     * @param string $passwordResetHash
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function index(string $passwordResetHash, Request $request, EntityManagerInterface $em) {
        /** @var SimpleUserInterface $user */
        $user = $em->getRepository($this->getParameter('simple_user.user_class'))
            ->findOneBy(['passwordResetHash' => $passwordResetHash]);
        if (!$user) {
            return $this->redirectToRoute($this->getParameter('simple_user_login'));
        }

        $form = $this->createForm(PasswordResetType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setPasswordResetHash(null);
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
            'user' => $user
        ]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function askNewPasswordResetLink(
        Request $request,
        EntityManagerInterface $em,
        MailerBuilderService $mailerBuilder
    ) {
        $form = $this->createForm(EmailType::class);
        $form->handleRequest($request);
        $user = null;
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SimpleUserInterface $role */
            $user = $em->getRepository($this->getParameter('simple_user.user_class'))
                ->findOneBy(
                    ['email' => $form->get('email')->getData()]
                );
            if ($user) {
                $user->setPasswordResetHash(HashHelper::createConfirmationHash());
                $mailerBuilder->setTemplate('@SimpleUser/Email/password_reset.html.twig', ['user' => $user])
                    ->setSubject('Reset password')
                    ->setEmailTo([$user->getEmail()])
                    ->setEmailFrom($this->getParameter('simple_user.email_from'))
                    ->send();
                $em->flush();
            }

            return $this->render('@SimpleUser/PasswordReset/askNewPasswordResetLink.html.twig',[
                'user' => $user
            ]);
        }

        return $this->render('@SimpleUser/PasswordReset/askNewPasswordResetLink.html.twig',[
            'form' => $form->createView(),
            'isAllowLogin' => $this->routeExists('simple_user_login'),
            'isAllowRegistration' => $this->routeExists('simple_user_registration')
        ]);
    }
}