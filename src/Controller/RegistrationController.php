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

class RegistrationController extends Controller
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function registerAction(Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userClass = $this->getParameter('simple_user.user_class');
            /** @var SimpleUserInterface $user */
            $user = new $userClass();
            $user->setPassword($form->get('_password')->getData());
            $user->setEmail($form->get('_username')->getData());
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
        }

        return $this->render('@SimpleUser/Registration/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}