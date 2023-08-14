<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AdressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController
{
    #[Route('compte/adresses', name: 'account_address')]
    public function index(): Response
    {
        return $this->render('account/address/index.html.twig', [
        ]);
    }

    #[Route('compte/ajouter-une-adresse', name: 'account_address_add')]
    public function add(Request $request,EntityManagerInterface $entityManager): Response
    {
        $defaultData = [
            // Ici, 'FR' correspond au code ISO 3166-1 alpha-2 de la France
            'pays' => 'FR'];
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
             $address->setUser($this->getUser());
            $entityManager->persist($address);
            $entityManager->flush();
            $this->addFlash('success', 'Votre adresse a bien été ajoutée');
            return $this->redirectToRoute('account_address');
}
        return $this->render('account/address/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('compte/modifier-une-adresse/{id}', name: 'account_address_edit')]
    public function update(Request $request,AdressRepository $adressRepository, Address $address
        ,EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $entityManager->persist($address);
            $entityManager->flush();
            $this->addFlash('success', 'Votre adresse a bien été ajoutée');
            return $this->redirectToRoute('account_address');
        }
        return $this->render('account/address/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('compte/delete/{id}', name: 'account_address_delete')]
    public function delete(Address $address,
                           EntityManagerInterface $entityManager): Response
    {

        if ( $address && $address->getUser() == $this->getUser()){
            $entityManager->remove($address);
            $entityManager->flush();
            $this->addFlash('success', 'Votre adresse a bien été supprimée');
            return $this->redirectToRoute('account_address');
        }
    }


}
