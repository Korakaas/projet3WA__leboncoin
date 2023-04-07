<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnnonceController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
            $this->em = $em;
    }

    #[Route('/annonce/{annonce}', name: 'app_annonce')]
    public function index(Annonce $annonce): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/user/{user}/annonces/', name: 'app_annonces_user')]
    public function userAnnonces(User $user, AnnonceRepository $annonceRepository): Response
    {
        $annonces = $annonceRepository->findBy(['user'=>$user->getId()]);
        return $this->render('annonce/userAnnonces.html.twig', [
            'annonces' => $annonces,
            'user'=>$user
        ]);
    }


    #[Route('/user/{user}/annonce/new', name:'app_annonce_new')]
    public function new(User $user, Request $request): Response
    {
        $annonce = new Annonce;
        $annonceForm = $this->createForm(AnnonceType::class, $annonce);
        $annonce->setUser($user);
        $annonceForm->handleRequest($request);
        if ($annonceForm->isSubmitted() && $annonceForm->isValid())
        {
            $this->em->persist($annonce);
            $this->em->flush();

            return $this->redirectToRoute("app_annonces_user", array('user' => $user->getId()));
        }
        return $this->render('annonce/new.html.twig', [
            'annonceForm' => $annonceForm,
        ]);
    }
}
