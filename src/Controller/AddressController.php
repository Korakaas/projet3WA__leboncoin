<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AddressController extends AbstractController
{

    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/user/{user}/address', name:'app_address', methods: ['GET'])]
    public function index(User $user, AddressRepository $addressRepository): Response
    {
        $this->denyAccessUnlessGranted('view', $user);
        $addresses = $addressRepository->findBy(['user' => $user->getId()]);
        return $this->render('address/index.html.twig', [
            'addresses' => $addresses,
            'user'=>$user
        ]);
    }

    #[Route('/user/{user}/address/new', name: 'app_address_new', methods: ['GET', 'POST'])]
    public function newAddress(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted('view', $user);
        $address = new Address();       
        $addressForm = $this->createForm(AddressType::class, $address);
        $address->setUser($user);
        $addressForm->handleRequest($request);
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->em->persist($address);
            $this->em->flush();
            return $this->redirectToRoute("app_address", array('user' => $user->getId()));
        }
        return $this->render('address/new.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }

    #[Route('/user/{user}/address/{address}/update', name: 'app_address_update', methods: ['GET', 'POST'])]
    public function updateAddress(Address $address, User $user, Request $request): Response
    {   $this->denyAccessUnlessGranted('view', $user);
        $addressForm = $this->createForm(AddressType::class, $address);
        $addressForm->handleRequest($request);
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->em->persist($address);
            $this->em->flush();
            return $this->redirectToRoute("app_address", array('user' => $user->getId()));
        }
        return $this->render('address/edit.html.twig', [
            'addressForm' => $addressForm->createView(),
        ]);
    }

    #[Route('/user/{user}/address/{address}/delete', name: 'app_address_delete', methods: ['GET', 'DELETE'])]
    public function deleteAddress(Address $address, User $user, Request $request): Response
    {   $this->denyAccessUnlessGranted('view', $user);
        
            $this->em->remove($address);
            $this->em->flush();
        

        return $this->redirectToRoute("app_address", array('user' => $user->getId()));
    }


    
}
