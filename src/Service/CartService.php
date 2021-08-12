<?php 

namespace App\Service;

use App\Entity\Book;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    private $sessionInterface;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface= $sessionInterface;
    }


    public function get()
    {

        $cart = $this->sessionInterface->get('cart');
        if ($cart === null)
        {
            $cart = [
                'total' => 0.0,
                'elements' => []
            ];
        }
        return $cart;
    }

    //ADD
    public function add(Book $book): void
    {
        //on récupère le panier s'il existe, sinon on en prend un nouveau
        $cart = $this->get();



        // ON ajoute le book s'il n'y en a pas 

        $bookId = $book->getId();
        if (!isset($cart['elements'][$bookId])) {
            $cart['elements'][$bookId] = [
                'book' => $book,
                'quantity' => 0
            ];
        }

        // On incrémente la quantity et on recalcule le prix total

        $cart['elements'][$bookId]['quantity'] = $cart['elements'][$bookId]['quantity'] + 1;
        $cart['total'] = $cart['total'] + $book->getPrice();

        // On sauvegarde le nouveau panier

        $this->sessionInterface->set('cart', $cart);

    }


    //REMOVE
    public function remove(Book $book): void
    {
        //on récupère le panier s'il existe, sinon on en prend un nouveau
        $cart = $this->get();

        $bookId = $book->getId();
        if (!isset($cart['elements'][$bookId])) {
            return;
        }

        // Il existe alors on met à jour les quantités
        $cart['total'] = $cart['total'] - $book->getPrice();
        $cart['elements'][$bookId]['quantity'] = $cart['elements'][$bookId]['quantity'] - 1;
        // si la quantité est de 0, on l'enlève  complètement du panier
        if ($cart['elements'][$bookId]['quantity'] <= 0) {
            unset($cart['elements'][$bookId]);
        }
        // On sauvegarde le panier
        $this->sessionInterface->set('cart', $cart);

    }

    // vider le panier

    public function clear()
    {

        $this->sessionInterface->remove('cart');

    }

    public function removeLine(Book $book)
    {
            //on récupère le panier s'il existe, sinon on en prend un nouveau
            $cart = $this->get();
            $bookId = $book->getId();
            if (!isset($cart['elements'][$bookId])) {
                 return;
            }
        $cart['total'] = $cart['total'] - $book->getPrice() * $cart['elements'][$bookId]['quantity'];
        unset($cart['elements'][$bookId]);
            // On sauvegarde le panier
            $this->sessionInterface->set('cart', $cart);
        
    }
}