<?php

namespace SimpleUser\Controller;

use Doctrine\ORM\EntityManagerInterface;
use SimpleUser\Form\UserType;
use SimpleUser\Helpers\UserRoleHelper;
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
        $userClass = $this->getParameter('simple_user.user_class');
        $user = new $userClass();
        $form = $this->createForm(UserType::class,  $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var SimpleUserInterface $user */
            $user = $form->getData();
            $user->addRole(UserRoleHelper::DEFAULT_ROLE_USER);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('@SimpleUser/Security/registration.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}