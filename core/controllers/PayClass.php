<?php

use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PPConnectionException;

class PayClass {

    private $payer;
    private $details;
    private $amount;
    private $transaction;
    private $payment;
    private $redirectUrls;

    public function __construct()
    {
        $this->payer = new Payer();
        $this->details = new Details();
        $this->amount = new Amount();
        $this->transaction = new Transaction();
        $this->payment = new Payment();
        $this->redirectUrls = new RedirectUrls();

        $this->payer->setPaymentMethod('paypal');
    }

    public function buyCredits($amount)
    {
        global $settings;

        $price = $amount / 5;
        $_SESSION['amount'] = $amount;

        $this->details->setShipping('0.00')
            ->setTax('0.00')
            ->setSubtotal($price);

        $this->amount->setCurrency('GBP')
            ->setTotal($price)
            ->setDetails($this->details);

        $this->transaction->setAmount($this->amount)
            ->setDescription($settings->config['SITE_NAME'].' credits');

        $this->payment->setIntent('sale')
            ->setPayer($this->payer)
            ->setTransactions(array($this->transaction));

        $this->redirectUrls->setReturnUrl($settings->config['WEB_ROOT']. 'pay/success')
            ->setCancelUrl($settings->config['WEB_ROOT']. 'pay/failed');

        $this->payment->setRedirectUrls($this->redirectUrls);

        $this->createPayment($settings->PayPal);
    }

    private function createPayment($api)
    {
        global $database, $user;

        try {

            $this->payment->create($api);

            $hash = md5($this->payment->getId());
            $_SESSION['hash'] = $hash;

            $database->query(
                "INSERT INTO ".TBL_PAYMENTS." SET uid = :uid, payment_id = :pid, hash = :hash",
                array(':uid' => $user->id, ':pid' => $this->payment->getId(), ':hash' => $hash)
            );

        } catch(PPConnectionException $e) {
            var_dump($e->getData());
        }

        foreach($this->payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirectUrl = $link->getHref();
            }
        }

        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$redirectUrl.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$redirectUrl.'" />';
        echo '</noscript>';
    }
}