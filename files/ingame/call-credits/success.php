<?php

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PPConnectionException;

if (isset($_GET['PayerID'])) {
    $payerID = $_GET['PayerID'];

    $paymentInfo = $database->query("SELECT payment_id, complete FROM ".TBL_PAYMENTS." WHERE hash = :hash", array(':hash' => $_SESSION['hash']))->fetchObject();

    if ($paymentInfo === false) {
        echo "This is not a valid payment. If this is a wrong error please contact the site owner first before the osFihgter developer (deesoomens@gmail.com)";
    } else if ($paymentInfo->complete != 0) {
        echo "This payment has already been made, if this is a wrong error please contact the site owner first before the osFihgter developer (deesoomens@gmail.com)";
    } else {
        try {
            $payment = Payment::get($paymentInfo->payment_id, $settings->PayPal);

            $execution = new PaymentExecution();
            $execution->setPayerId($payerID);

            $payment->execute($execution, $settings->PayPal);

            $database->query(
                "UPDATE " . TBL_PAYMENTS . " SET complete = 1, date_completed = :date WHERE payment_id = :pid",
                array(':pid' => $paymentInfo->payment_id, ':date' => time())
            );

            $user->stats->credits = $user->stats->credits + $_SESSION['amount'];
            $items = array(':credits' => $user->stats->credits, ':uid' => $user->id);
            $database->query("UPDATE " . TBL_INFO . " SET credits = :credits WHERE uid = :uid", $items);

            unset($_SESSION['amount']);
            unset($_SESSION['hash']);

            echo "You have payed with success! Your credits have be added to your account.";
        } catch (PPConnectionException $e) {
            echo $e->getData();
        }
    }
}