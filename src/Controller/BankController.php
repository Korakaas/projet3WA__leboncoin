<?php

namespace App\Controller;

use App\Entity\Bank;
use App\Entity\User;
use App\Form\BankType;
use App\Repository\BankRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BankController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user/{user}/bank', name: 'app_bank')]
    public function index(User $user, BankRepository $bankRepository): Response
    {
        $this->denyAccessUnlessGranted('view', $user);
        $bank = $bankRepository->findOneBy(['user' => $user->getId()]);
        return $this->render('bank/index.html.twig', [
            'bank' => $bank,
            'user' => $user,
        ]);
    }

    #[Route('/user/{user}/bank/{bank}/update', name: 'app_bank_update')]
    public function update(User $user, Bank $bank, Request $request): Response
    {    
        $this->denyAccessUnlessGranted('view', $user);
        $currentAmount = $bank->getAmount();
        $bankForm = $this->createForm(BankType::class, $bank);
        $bank->setUser($user);
        $bankForm->handleRequest($request);
        if ($bankForm->isSubmitted() && $bankForm->isValid()) {
            $bank->setAmount($currentAmount + $bankForm->get('montant')->getData());
            $this->em->persist($bank);
            $this->em->flush();
            return $this->redirectToRoute("app_bank", array('user' => $user->getId()));
        }
        return $this->render('bank/edit.html.twig', [
            'bankForm' => $bankForm->createView(),
        ]);
    }
}
