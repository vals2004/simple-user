<?php

namespace SimpleUser\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SimpleUser\Form\UserType;
use SimpleUser\Helpers\UserRoleHelper;
use SimpleUser\Interfaces\SimpleUserRoleInterface;
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
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $entityManager)
    {
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

            $token = new UsernamePasswordToken(
                $user,
                null,
                $this->getParameter('simple_user.firewall_name'),
                $user->getRoles()
            );
            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set(
                '_security_' . $this->getParameter('simple_user.firewall_name'), serialize($token)
            );

            return $this->redirect($request->getSession()
                ->get(
                    sprintf('_security.%s.target_path', $this->getParameter('simple_user.firewall_name'))
                )
            );
        }

        return $this->render('@SimpleUser/Registration/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}