<?php

namespace App\Controller;

use DateTime;
use App\Entity\Commande;
use App\Service\CartService;
use App\Entity\CommandeDetail;
use App\Repository\BookRepository;
use Stripe\BillingPortal\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/success/{stripeSessionId}", name="payment_success")
     */
    public function success(string $stripeSessionId, CartService $cartService, BookRepository $bookRepository): Response
    {
        // on travaillera ici
        $entitymanager = $this->getDoctrine()->getManager();
        $panier = $cartService->get();
        $time = new DateTime('NOW');
        $commande = new Commande;
        $commande->setCreatedAt($time);
        $commande->setReference($stripeSessionId);
        foreach($panier['elements'] as $element){
            $commandeDetail = new CommandeDetail;
            $commandeDetail->setQuantity($element['quantity']);
            $commande->addCommandeDetail($commandeDetail);
            $book = $bookRepository->find($element['book']->getId());
            $book->addCommandeDetail($commandeDetail);
            $entitymanager->persist($book);
           
        }
        $entitymanager->persist($commande);
        $entitymanager->flush();


        
        return $this->render('payment/success.html.twig', [
            'controller_name' => 'PaymentController',
        ]);

    }
        /**
     * @Route("/payment/failure/{stripeSessionId}", name="payment_failure")
     */
    public function failure(): Response
    {
        return $this->render('payment/failure.html.twig', [
            'controller_name' => 'PaymentController',
        ]);

    }
}
