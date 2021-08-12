<?php 

namespace App\Service;

use Stripe\StripeClient;



class PaymentService
{
    private $carteService;
    private $stripe;


    public function __construct(CartService $carteService)
    {
        $this->carteService = $carteService;
        $this->stripe = new StripeClient('sk_test_51JNEKxJ4AWmTo1wzsBpbFayqC4PlUszrB1z47nDUMMEVFtIZ9htTlFyCZWWaidHRcPFW2EgglQeH8Ql0NqfRkJXm00ypgI1Lg1');
    }

    // function create une session de paiement stripe

    public function create(): string
    {
        // 1. success URL 
        // http://localhost/symfony/exo1/public/payment/failure/sdfqdsazer
        $protocol = 'http';
        if(isset($_SERVER['HTTPS']))
        {
            $protocol = 'https';
        }
        $serverName = $_SERVER['SERVER_NAME'];
    
        $successUrl = $protocol . '://' . $serverName . '/symfony/exo1/public/payment/success/{CHECKOUT_SESSION_ID}';

        // 2. failure URL

        $protocol = 'http';
        if(isset($_SERVER['HTTPS']))
        {
            $protocol = 'https';
        }
        $serverName = $_SERVER['SERVER_NAME'];
    
        $cancelUrl = $protocol . '://' . $serverName . '/symfony/exo1/public/payment/failure/{CHECKOUT_SESSION_ID}';

        // 3. Elements (détail du panier)

        /**
         * 1 item :
         * amount : le prix de l'article
         * quantity : la quantite de l'article
         * currency 'eur
         * name : le nom de l'article
         */

        $items = [];
        $panier = $this->carteService->get();
        foreach($panier['elements'] as $element)
        {
            $item = [
                'amount' => $element['book']->getPrice() * 100,
                'quantity' => $element['quantity'],
                'currency' => 'eur',
                'name' => $element['book']->getTitle()
            ];

            // array_push($items, $item);
            $items[] = $item;
        }

        
        $session = $this->stripe->checkout->sessions->create([

            'success_url' =>$successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $items

        ]);

        return $session->id;
    }



}