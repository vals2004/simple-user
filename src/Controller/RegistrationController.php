<?php

namespace SimpleUser\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SimpleUser\Form\UserType;
use SimpleUser\Helpers\UserRoleHelper;
use SimpleUser\Interfaces\SimpleUserRoleInterface;
use SimpleUser\Service\MailerBuilderService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SimpleUser\Interfaces\SimpleUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class RegistrationController extends Controller
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param MailerBuilderService $mailerBuilder
     * @return Response
     */
    public function index(
        Request $request,
        EntityManagerInterface $entityManager,
        MailerBuilderService $mailerBuilder
    ) {
        $userClass = $this->getParameter('simple_user.user_class');
        /** @var SimpleUserInterface $user */
        $user = new $userClass();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            /** @var SimpleUserRoleInterface $role */
            $role = $entityManager->getRepository($this->getParameter('simple_user.role_class'))
                ->findOneBy(
                    ['name' => UserRoleHelper::DEFAULT_ROLE_USER]
                );
            if ($role) {
                $user->addRole($role);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $mailerBuilder->setTemplate('@SimpleUser/Email/registration.html.twig', ['user' => $user])
                ->setSubject('Registration complete')
                ->setEmailTo([$user->getEmail()])
                ->setEmailFrom($this->getParameter('simple_user.email_from'))
                ->send();

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

        return $this->render('@SimpleUser/Registration/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $confirmHash
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function complete(
        string $confirmHash,
        EntityManagerInterface $em
    ) {
        /** @var SimpleUserInterface $user */
        $user = $em->getRepository($this->getParameter('simple_user.user_class'))
            ->findOneBy(['confirmHash' => $confirmHash]);

        if ($user) {
            $user->setConfirmHash(null);
            $user->setEnabled(true);
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

        return $this->redirectToRoute('simple_user_login');
    }
}