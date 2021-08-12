<?php

namespace App\Controller;

use App\Entity\Book;
use App\Service\CartService;
use App\Service\PaymentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier_index")
     */
    public function index(CartService $cartService): Response
    {
        //on récupère le panier s'il existe, sinon on en prend un nouveau
        // $cart = $sessionInterface->get('cart', [

        //     'total' => 0.0,
        //     'elements' => [],
        // ]);

        $cart = $cartService->get();

        return $this->render('panier/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}", name="panier_add")
     */

    public function add(Book $book, CartService $cartService): Response
    {
        // //on récupère le panier s'il existe, sinon on en prend un nouveau
        // $cart = $sessionInterface->get('cart', [

        //     'total' => 0.0,
        //     'elements' => [],
        // ]);


        // // ON ajoute le book s'il n'y en a pas 

        // $bookId = $book->getId();
        // if (!isset($cart['elements'][$bookId])) {
        //     $cart['elements'][$bookId] = [
        //         'book' => $book,
        //         'quantity' => 0
        //     ];
        // }

        // // On incrémente la quantity et on recalcule le prix total

        // $cart['elements'][$bookId]['quantity'] = $cart['elements'][$bookId]['quantity'] + 1;
        // $cart['total'] = $cart['total'] + $book->getPrice();

        // // On sauvegarde le nouveau panier

        // $sessionInterface->set('cart', $cart);

        // // On redirige vers la page panier
        $cartService->add($book);
        return $this->redirectToRoute("panier_index");
    }


    /**
     * @Route("/panier/remove/{id}", name="panier_remove")
     */

    public function remove(Book $book, CartService $cartService): Response
    {
        // //on récupère le panier s'il existe, sinon on en prend un nouveau
        // $cart = $sessionInterface->get('cart', [

        //     'total' => 0.0,
        //     'elements' => [],
        // ]);

        // on ne fait rien si le livre n'est pas dans le panier
        $cartService->remove($book);
        // $bookId = $book->getId();
        // if (!isset($cart['elements'][$bookId])) {
        //     return $this->redirectToRoute('panier_index');
        // }
        // // Il existe alors on met à jour les quantités
        // $cart['total'] = $cart['total'] - $book->getPrice();
        // $cart['elements'][$bookId]['quantity'] = $cart['elements'][$bookId]['quantity'] - 1;
        // // si la quantité est de 0, on l'enlève  complètement du panier
        // if ($cart['elements'][$bookId]['quantity'] <= 0) {
        //     unset($cart['elements'][$bookId]);
        // }
        // // On sauvegarde le panier
        // $sessionInterface->set('cart', $cart);
        // // On redirige l'utilisateur vers la page index du panier
        return $this->redirectToRoute("panier_index");
    }

    /**
     * @Route("/panier/clear/", name="panier_clear")
     */
    // VIDER LE PANIER

    public function clear(CartService $cartService): Response
    {
        //on récupère le panier s'il existe, sinon on en prend un nouveau
        // $cart = $sessionInterface->get('cart', [

        //     'total' => 0.0,
        //     'elements' => [],
        // ]);

        $cartService->clear();
        // $bookId = $book->getId();
        // // on ne fait rien si le livre n'est pas dans le panier
        // if (!isset($cart['elements'][$bookId])) {
        //     return $this->redirectToRoute('panier_index');
        // }

        // // on met a jour le panier
        // $cart['total'] = $cart['total'] - $book->getPrice() * $cart['elements'][$bookId]['quantity'];
        // unset($cart['elements'][$bookId]);

        // //On enregistre le panier
        // $sessionInterface->set('cart', $cart);

        //on redirige
        return $this->redirectToRoute("panier_index");
    }

    public function removeLine(Book $book, CartService $cartService): Response
    {
        $cartService->removeLine($book);
        return $this->redirectToRoute("panier_index");
    }

    /**
     * @Route("/panier/valider", name="panier_valide")
     */
    public function validate(PaymentService $paymentService): Response
    {
     $stripeSessionService = $paymentService->create();

     return $this->render('panier/redirect.html.twig', [
         'stripeSessionId' => $stripeSessionService
     ]);
    }
}
