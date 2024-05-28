<?php

/**
 * WHMCS Sample Payment Callback File
 *
 * This sample file demonstrates how a payment gateway callback should be
 * handled within WHMCS.
 *
 * It demonstrates verifying that the payment gateway module is active,
 * validating an Invoice ID, checking for the existence of a Transaction ID,
 * Logging the Transaction for debugging and Adding Payment to an Invoice.
 *
 * For more information, please refer to the online documentation.
 *
 * @see https://developers.whmcs.com/payment-gateways/callbacks/
 *
 * @copyright Copyright (c) WHMCS Limited 2017
 * @license http://www.whmcs.com/license/ WHMCS Eula
 */

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

use Symfony\Component\HttpFoundation\Response;
use WHMCS\Database\Capsule;
use WHMCS\Billing\Invoice;


// Check if orderId parameter is provided in the URL
if (empty($_GET['orderId'])) {
  exit();
}

// Function to send JSON response
function response($data, $code = 200)
{
  (
    new Response(
      json_encode($data),
    $code,
      ['content-type' => 'application/json']
    )
  )->send();

  die;
}

// Retrieve the orderId from the URL
$invoiceId = @$_GET['orderId'];
$order = Invoice::find($invoiceId);

// Check if the invoice has already been paid
if ($order->status == 'Paid') {
  $paymentSuccess = true;

  // If autoaccept is enabled and the invoice status is "Pending", accept the order

  response([
    'code' => 200,
    'message' => 'Hóa đơn đã được thanh toán!'
  ], 200);

} else {
  // If the invoice is not paid, return a response with code 400
  response([
    'code' => 400,
    'message' => 'Không có giao dịch hiện tại!'
  ], 200);
  $paymentSuccess = false;
}