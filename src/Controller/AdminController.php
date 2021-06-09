<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_index")
     */
    public function index(Security $security): Response
    {
        $impersonateUser = null;

        if ($security->getToken() instanceof SwitchUserToken) {
            $impersonateUser = $security->getToken()->getOriginalToken()->getUser();
        }

        return $this->render(
            'admin/index.html.twig',
            [
                'impersonate_user' => $impersonateUser,
            ]
        );
    }
}