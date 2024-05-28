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

// Detect module name from filename.
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);


// Die if module is not active.
if (!$gatewayParams['type']) {
  die("Module Not Activated");
}
function response($data, $code = 200)
{
  (new Response(
    json_encode($data),
    $code,
    ['content-type' => 'application/json']
  ))->send();

  die;
}
// Retrieve data returned in payment gateway callback
// Varies per payment gateway


/**
 * Validate callback authenticity.
 *
 * Most payment gateways provide a method of verifying that a callback
 * originated from them. In the case of our example here, this is achieved by
 * way of a shared secret which is used to build and compare a hash.
 */


/**
 * Validate Callback Invoice ID.
 *
 * Checks invoice ID is a valid invoice number. Note it will count an
 * invoice in any status as valid.
 *
 * Performs a die upon encountering an invalid Invoice ID.
 *
 * Returns a normalised invoice ID.
 *
 * @param int $invoiceId Invoice ID
 * @param string $gatewayName Gateway Name
 */


/**
 * Check Callback Transaction ID.
 *
 * Performs a check for any existing transactions with the same given
 * transaction number.
 *
 * Performs a die upon encountering a duplicate.
 *
 * @param string $transactionId Unique Transaction ID
 */

/**
 * Log Transaction.
 *
 * Add an entry to the Gateway Log for debugging purposes.
 *
 * The debug data can be a string or an array. In the case of an
 * array it will be
 *
 * @param string $gatewayName        Display label
 * @param string|array $debugData    Data to log
 * @param string $transactionStatus  Status
 */

$username = $gatewayParams['username'];
$password = $gatewayParams['password'];
$token = $gatewayParams['token'];
$stk = $gatewayParams['stk'];
$endpoint = $gatewayParams['endpoint'];
$invoiceId = @$_GET['orderId'];
$array = array(
  '{{orderid}}' => $invoiceId,
);
$noidung = strtr($gatewayParams['noidung'], $array);
$order = Invoice::find($invoiceId);
$Amount =  intval($order->total);
$url = $endpoint."/" . $token;
$header = array(
  'Content-Type: application/json'
);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Mobile Safari/537.36");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

$response = curl_exec($ch);
curl_close($ch);
//echo $response;
//echo $url = $gatewayParams['systemurl'] . "modules/gateways/callback/request.php?&orderId=" . $orderId;
$datacheck = json_decode($response, true);
$ChiTietGiaoDich = $datacheck['data']['ChiTietGiaoDich'];
foreach ($ChiTietGiaoDich as $value) {
  if (strtoupper($noidung) == strtoupper(substr(strtoupper($value["MoTa"]), strpos(strtoupper($value["MoTa"]), $noidung), strlen($noidung)))) {
    $data1 = [
      'code1'   => 200,
      'sotien' => (int)str_replace(',', '', $value['SoTienGhiCo']),
      'magiaodich' => $value['SoThamChieu'],
      'stk' => $value["SoTaiKhoan"],
      'noidung' => strtoupper($value["MoTa"]),
    ];
    break;
  } else {
    $data1 = ["code" => '500'];
  }
}
$paymentFee = '0';
$gatewayModuleName = 'acb';
$transactionId = $data1['magiaodich'];
$paymentAmount = $data1['sotien'];
if ($data1['code1'] == '200') {
  if ($order->status == 'Paid') {
    $paymentSuccess = true;
    $data = array("code" => 200);
    echo json_encode($data);
  } else {
    if ($Amount <= $data1['sotien']) {
      $transid = Capsule::table('tblaccounts')->where('transid', '=', $transactionId)->first();
      logTransaction($gatewayModuleName, [
        'transactionId' => $transactionId,
        'gateway' => $gatewayModuleName,
        'money' => $paymentAmount,
        'invoiceId' => $invoiceId,
        'type' => 'Payment Page'
      ], 'Success');
      if (!$transid) {
      addInvoicePayment(
        $invoiceId,
        $transactionId,
        $paymentAmount,
        $paymentFee,
        $gatewayModuleName
      );
    }
      $paymentSuccess = true;
      response([
        'code' => 200,
        'message' => 'Invoice has paid successfully!'
      ], 200);
    } else {
      logTransaction($gatewayModuleName, [
        'transactionId' => $transactionId,
        'gateway' => $gatewayModuleName,
        'money' => $paymentAmount,
        'invoiceId' => $invoiceId
      ], 'Not enough money to pay order');
      response([
        'code' => 400,
        'message' => 'Not enough money to pay order!'
      ], 200);
      $paymentSuccess = false;
    }
  }
} else {
  response([
    'code' => 400,
    'message' => 'No existing transactions!'
  ], 200);
  $paymentSuccess = false;
}
