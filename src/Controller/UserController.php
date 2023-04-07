<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

    private UserRepository $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
{
    $this->userRepository = $userRepository;
}

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        $user = $this->userRepository->find($this->getUser());
        // dd($user);
        $this->denyAccessUnlessGranted('view', $user);
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
