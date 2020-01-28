<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement", name="paiement")
     */
    public function index()
    {
        \Stripe\Stripe::setApiKey('sk_test_vxdH3rGrgixkaLlKQyQ2q16000FvZJtNhP');

        // `source` is obtained with Stripe.js; see https://stripe.com/docs/payments/accept-a-payment-charges#web-create-token
        \Stripe\Charge::create([
        'amount' => 100,
        'currency' => 'eur',
        'source' => 'tok_mastercard',
        'description' => 'paiement de test',
        ]);
        return $this->render('paiement.html.twig');
    }
}
