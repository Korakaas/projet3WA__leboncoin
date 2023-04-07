<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    public function __construct(
        private Security $security,
    ){
    }

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->security->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
