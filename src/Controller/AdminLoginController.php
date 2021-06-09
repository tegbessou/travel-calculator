<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminLoginController extends AbstractController
{
    /**
     * @Route(name="app_admin_login", "/admin/login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class);

        return $this->render(
            'admin-login/login.html.twig',
            [
                'form' => $form->createView(),
                'error' => $authenticationUtils->getLastAuthenticationError(),
            ]
        );
    }

    /**
     * @Route(name="app_admin_logout", "/admin/logout")
     */
    public function logout()
    {
        throw new \LogicException('This method doesn\'t have to be called!');
    }
}