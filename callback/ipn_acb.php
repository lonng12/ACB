<?php

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

use Symfony\Component\HttpFoundation\Response;
use WHMCS\Database\Capsule;
use WHMCS\Billing\Invoice;

// Khai báo tên cổng thanh toán
$gatewayModuleName = 'acb';

// Lấy thông tin cấu hình.
$gatewayParams = getGatewayVariables($gatewayModuleName);

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

function parse_order_id($des, $MEMO_PREFIX)
{
    $re = '/' . $MEMO_PREFIX . '\d+/im';
    preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);
    if (count($matches) == 0)
        return null;
    // Print the entire match result
    $orderCode = $matches[0][0];
    $prefixLength = strlen($MEMO_PREFIX);
    $orderId = intval(substr($orderCode, $prefixLength));
    return $orderId;
}

function verify_license_key($key)
{
    $url = "https://api.licensegate.io/license/a1cc8/c07cbb30-61d4-4909-8f39-80d89537c42d/verify?key=" . urlencode($key);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);

    return isset($data['valid']) && $data['valid'];
}

// Die if module is not active.
if (!$gatewayParams['type']) {
    response([
        'success' => false,
        'message' => 'Cổng thanh toán ACB chưa được kích hoạt !'
    ], 428);
}

// Kiểm tra key kích hoạt
$licenseKey = $gatewayParams['license_key'];
if (!verify_license_key($licenseKey)) {
    $systemUrl = $gatewayParams['systemurl'];
    $returnUrl = $systemUrl . 'modules/gateways/acb/invalid_key.php';
    header("Location: $returnUrl");
    exit();
}

$token = $gatewayParams['token'];
$password = $gatewayParams['password'];
$stk = $gatewayParams['stk'];
$endpoint = "https://api.dptcloud.vn/historyapiacb";

// mẫu cú pháp check
$replacedSyntax = preg_replace(['#{{orderid}}#'], '', $gatewayParams['noidung']);
$url = $endpoint . "/" . $token;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Mobile Safari/537.36");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);
foreach ($result['data'] as $value) {
    $transactionId = $value['transactionNumber'];
    $invoiceId = parse_order_id(strtoupper($value["description"]), strtoupper($replacedSyntax));
    if (empty($invoiceId)) {
        response([
            'success' => false,
            'message' => 'Không phát hiện dữ liệu hóa đơn !'
        ], 404);
    }
    $order = Invoice::find($invoiceId);
    if (!$order) {
        response([
            'success' => false,
            'message' => 'Không tìm thấy hóa đơn hiện có !'
        ], 404);
    }
    if ($order->status == 'Cancelled') {
        response([
            'success' => false,
            'message' => 'Hóa đơn đã hủy !'
        ], 201);
    }
    if ($order->status == 'Paid') {
        response([
            'success' => false,
            'message' => 'Hóa đơn đã thanh toán !'
        ], 201);
    }
    $total = $order->balance;

    if (intval($total) <= (int) str_replace(',', '', $value['amount'])) {
        checkCbTransID($transactionId);
        logTransaction($gatewayModuleName, [
            'transactionId' => $value['transactionNumber'],
            'Cổng Thanh Toán' => $gatewayModuleName,
            'Số Tiền' => (int) str_replace(',', '', $value['amount']),
            'Hoá Đơn' => $invoiceId,
        ], 'Thành Công');
        addInvoicePayment(
            $order->id,
            $value['transactionNumber'],
            (int) str_replace(',', '', $value['amount']),
            0,
            $gatewayModuleName
        );
        response([
            'success' => true,
            'message' => 'Hóa đơn đã thanh toán thành công !'
        ]);
    } else {
        logTransaction($gatewayModuleName, [
            'transactionId' => $value['transactionNumber'],
            'gateway' => $gatewayModuleName,
            'money' => (int) str_replace(',', '', $value['amount']),
            'invoiceId' => $invoiceId
        ], 'Không đủ tiền để thanh toán đơn hàng !');
        response([
            'success' => false,
            'message' => 'Không đủ tiền để thanh toán đơn hàng !'
        ], 402);
    }
}
